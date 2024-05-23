<?php

/**
 * This is the model class for table "booking_cities_stats".
 *
 * The followings are the available columns in table 'booking_cities_stats':
 * @property integer $bcs_id
 * @property integer $bcs_cty_id
 * @property integer $bcs_source_Count
 * @property integer $bcs_dest_Count
 * @property integer $bcs_OW_source_curr_month_pickup_Count
 * @property integer $bcs_RT_source_curr_month_pickup_Count
 * @property integer $bcs_AT_source_curr_month_pickup_Count
 * @property integer $bcs_PT_source_curr_month_pickup_Count
 * @property integer $bcs_FL_source_curr_month_pickup_Count
 * @property integer $bcs_SH_source_curr_month_pickup_Count
 * @property integer $bcs_CT_source_curr_month_pickup_Count
 * @property integer $bcs_DR_source_curr_month_pickup_4HR_Count
 * @property integer $bcs_DR_source_curr_month_pickup_8HR_Count
 * @property integer $bcs_DR_source_curr_month_pickup_12HR_Count
 * @property integer $bcs_AP_source_curr_month_pickup_Count
 * @property integer $bcs_OW_source_prev_3month_pickup_Count
 * @property integer $bcs_RT_source_prev_3month_pickup_Count
 * @property integer $bcs_AT_source_prev_3month_pickup_Count
 * @property integer $bcs_PT_source_prev_3month_pickup_Count
 * @property integer $bcs_FL_source_prev_3month_pickup_Count
 * @property integer $bcs_SH_source_prev_3month_pickup_Count
 * @property integer $bcs_CT_source_prev_3month_pickup_Count
 * @property integer $bcs_DR_source_prev_3month_pickup_4HR_Count
 * @property integer $bcs_DR_source_prev_3month_pickup_8HR_Count
 * @property integer $bcs_DR_source_prev_3month_pickup_12HR_Count
 * @property integer $bcs_AP_source_prev_3month_pickup_Count
 * @property integer $bcs_OW_source_curr_month_create_Count
 * @property integer $bcs_RT_source_curr_month_create_Count
 * @property integer $bcs_AT_source_curr_month_create_Count
 * @property integer $bcs_PT_source_curr_month_create_Count
 * @property integer $bcs_FL_source_curr_month_create_Count
 * @property integer $bcs_SH_source_curr_month_create_Count
 * @property integer $bcs_CT_source_curr_month_create_Count
 * @property integer $bcs_DR_source_curr_month_create_4HR_Count
 * @property integer $bcs_DR_source_curr_month_create_8HR_Count
 * @property integer $bcs_DR_source_curr_month_create_12HR_Count
 * @property integer $bcs_AP_source_curr_month_create_Count
 * @property integer $bcs_OW_source_prev_3month_create_Count
 * @property integer $bcs_RT_source_prev_3month_create_Count
 * @property integer $bcs_AT_source_prev_3month_create_Count
 * @property integer $bcs_PT_source_prev_3month_create_Count
 * @property integer $bcs_FL_source_prev_3month_create_Count
 * @property integer $bcs_SH_source_prev_3month_create_Count
 * @property integer $bcs_CT_source_prev_3month_create_Count
 * @property integer $bcs_DR_source_prev_3month_create_4HR_Count
 * @property integer $bcs_DR_source_prev_3month_create_8HR_Count
 * @property integer $bcs_DR_source_prev_3month_create_12HR_Count
 * @property integer $bcs_AP_source_prev_3month_create_Count

 * @property integer $bcs_OW_dest_curr_month_pickup_Count
 * @property integer $bcs_RT_dest_curr_month_pickup_Count
 * @property integer $bcs_AT_dest_curr_month_pickup_Count
 * @property integer $bcs_PT_dest_curr_month_pickup_Count
 * @property integer $bcs_FL_dest_curr_month_pickup_Count
 * @property integer $bcs_SH_dest_curr_month_pickup_Count
 * @property integer $bcs_CT_dest_curr_month_pickup_Count
 * @property integer $bcs_DR_dest_curr_month_pickup_4HR_Count
 * @property integer $bcs_DR_dest_curr_month_pickup_8HR_Count
 * @property integer $bcs_DR_dest_curr_month_pickup_12HR_Count
 * @property integer $bcs_AP_dest_curr_month_pickup_Count
 * @property integer $bcs_OW_dest_prev_3month_pickup_Count
 * @property integer $bcs_RT_dest_prev_3month_pickup_Count
 * @property integer $bcs_AT_dest_prev_3month_pickup_Count
 * @property integer $bcs_PT_dest_prev_3month_pickup_Count
 * @property integer $bcs_FL_dest_prev_3month_pickup_Count
 * @property integer $bcs_SH_dest_prev_3month_pickup_Count
 * @property integer $bcs_CT_dest_prev_3month_pickup_Count
 * @property integer $bcs_DR_dest_prev_3month_pickup_4HR_Count
 * @property integer $bcs_DR_dest_prev_3month_pickup_8HR_Count
 * @property integer $bcs_DR_dest_prev_3month_pickup_12HR_Count
 * @property integer $bcs_AP_dest_prev_3month_pickup_Count
 * @property integer $bcs_OW_dest_curr_month_create_Count
 * @property integer $bcs_RT_dest_curr_month_create_Count
 * @property integer $bcs_AT_dest_curr_month_create_Count
 * @property integer $bcs_PT_dest_curr_month_create_Count
 * @property integer $bcs_FL_dest_curr_month_create_Count
 * @property integer $bcs_SH_dest_curr_month_create_Count
 * @property integer $bcs_CT_dest_curr_month_create_Count
 * @property integer $bcs_DR_dest_curr_month_create_4HR_Count
 * @property integer $bcs_DR_dest_curr_month_create_8HR_Count
 * @property integer $bcs_DR_dest_curr_month_create_12HR_Count
 * @property integer $bcs_AP_dest_curr_month_create_Count
 * @property integer $bcs_OW_dest_prev_3month_create_Count
 * @property integer $bcs_RT_dest_prev_3month_create_Count
 * @property integer $bcs_AT_dest_prev_3month_create_Count
 * @property integer $bcs_PT_dest_prev_3month_create_Count
 * @property integer $bcs_FL_dest_prev_3month_create_Count
 * @property integer $bcs_SH_dest_prev_3month_create_Count
 * @property integer $bcs_CT_dest_prev_3month_create_Count
 * @property integer $bcs_DR_dest_prev_3month_create_4HR_Count
 * @property integer $bcs_DR_dest_prev_3month_create_8HR_Count
 * @property integer $bcs_DR_dest_prev_3month_create_12HR_Count
 * @property integer $bcs_AP_dest_prev_3month_create_Count
 * 
 * @property string $bcs_last_bkg_src_complete_date
 * @property string $bcs_last_bkg_src_create_date
 * @property string $bcs_last_bkg_dst_complete_date
 * @property string $bcs_last_bkg_dst_create_date
 * @property string $bcs_create_date
 * @property string $bcs_modified_date
 * @property integer $bcs_active
 */
class BookingCitiesStats extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'booking_cities_stats';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('bcs_cty_id', 'required'),
            array('bcs_id, bcs_cty_id, bcs_source_Count, bcs_dest_Count, bcs_OW_source_curr_month_pickup_Count, bcs_RT_source_curr_month_pickup_Count, bcs_AT_source_curr_month_pickup_Count, bcs_PT_source_curr_month_pickup_Count, bcs_FL_source_curr_month_pickup_Count, bcs_SH_source_curr_month_pickup_Count, bcs_CT_source_curr_month_pickup_Count, bcs_DR_source_curr_month_pickup_4HR_Count, bcs_DR_source_curr_month_pickup_8HR_Count, bcs_DR_source_curr_month_pickup_12HR_Count, bcs_AP_source_curr_month_pickup_Count, bcs_OW_source_prev_3month_pickup_Count, bcs_RT_source_prev_3month_pickup_Count, bcs_AT_source_prev_3month_pickup_Count, bcs_PT_source_prev_3month_pickup_Count, bcs_FL_source_prev_3month_pickup_Count, bcs_SH_source_prev_3month_pickup_Count, bcs_CT_source_prev_3month_pickup_Count, bcs_DR_source_prev_3month_pickup_4HR_Count, bcs_DR_source_prev_3month_pickup_8HR_Count, bcs_DR_source_prev_3month_pickup_12HR_Count, bcs_AP_source_prev_3month_pickup_Count, bcs_OW_source_curr_month_create_Count, bcs_RT_source_curr_month_create_Count, bcs_AT_source_curr_month_create_Count, bcs_PT_source_curr_month_create_Count, bcs_FL_source_curr_month_create_Count, bcs_SH_source_curr_month_create_Count, bcs_CT_source_curr_month_create_Count, bcs_DR_source_curr_month_create_4HR_Count, bcs_DR_source_curr_month_create_8HR_Count, bcs_DR_source_curr_month_create_12HR_Count, bcs_AP_source_curr_month_create_Count, bcs_OW_source_prev_3month_create_Count, bcs_RT_source_prev_3month_create_Count, bcs_AT_source_prev_3month_create_Count, bcs_PT_source_prev_3month_create_Count, bcs_FL_source_prev_3month_create_Count, bcs_SH_source_prev_3month_create_Count, bcs_CT_source_prev_3month_create_Count, bcs_DR_source_prev_3month_create_4HR_Count, bcs_DR_source_prev_3month_create_8HR_Count, bcs_DR_source_prev_3month_create_12HR_Count, bcs_AP_source_prev_3month_create_Count, bcs_active,bcs_OW_dest_curr_month_pickup_Count, bcs_RT_dest_curr_month_pickup_Count, bcs_AT_dest_curr_month_pickup_Count, bcs_PT_dest_curr_month_pickup_Count, bcs_FL_dest_curr_month_pickup_Count, bcs_SH_dest_curr_month_pickup_Count, bcs_CT_dest_curr_month_pickup_Count, bcs_DR_dest_curr_month_pickup_4HR_Count, bcs_DR_dest_curr_month_pickup_8HR_Count, bcs_DR_dest_curr_month_pickup_12HR_Count, bcs_AP_dest_curr_month_pickup_Count, bcs_OW_dest_prev_3month_pickup_Count, bcs_RT_dest_prev_3month_pickup_Count, bcs_AT_dest_prev_3month_pickup_Count, bcs_PT_dest_prev_3month_pickup_Count, bcs_FL_dest_prev_3month_pickup_Count, bcs_SH_dest_prev_3month_pickup_Count, bcs_CT_dest_prev_3month_pickup_Count, bcs_DR_dest_prev_3month_pickup_4HR_Count, bcs_DR_dest_prev_3month_pickup_8HR_Count, bcs_DR_dest_prev_3month_pickup_12HR_Count, bcs_AP_dest_prev_3month_pickup_Count, bcs_OW_dest_curr_month_create_Count, bcs_RT_dest_curr_month_create_Count, bcs_AT_dest_curr_month_create_Count, bcs_PT_dest_curr_month_create_Count, bcs_FL_dest_curr_month_create_Count, bcs_SH_dest_curr_month_create_Count, bcs_CT_dest_curr_month_create_Count, bcs_DR_dest_curr_month_create_4HR_Count, bcs_DR_dest_curr_month_create_8HR_Count, bcs_DR_dest_curr_month_create_12HR_Count, bcs_AP_dest_curr_month_create_Count, bcs_OW_dest_prev_3month_create_Count, bcs_RT_dest_prev_3month_create_Count, bcs_AT_dest_prev_3month_create_Count, bcs_PT_dest_prev_3month_create_Count, bcs_FL_dest_prev_3month_create_Count, bcs_SH_dest_prev_3month_create_Count, bcs_CT_dest_prev_3month_create_Count, bcs_DR_dest_prev_3month_create_4HR_Count, bcs_DR_dest_prev_3month_create_8HR_Count, bcs_DR_dest_prev_3month_create_12HR_Count, bcs_AP_dest_prev_3month_create_Count', 'numerical', 'integerOnly' => true),
            array('bcs_last_bkg_src_complete_date, bcs_last_bkg_src_create_date, bcs_last_bkg_dst_complete_date, bcs_last_bkg_dst_create_date, bcs_create_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('bcs_id, bcs_cty_id, bcs_source_Count, bcs_dest_Count, bcs_OW_source_curr_month_pickup_Count, bcs_RT_source_curr_month_pickup_Count, bcs_AT_source_curr_month_pickup_Count, bcs_PT_source_curr_month_pickup_Count, bcs_FL_source_curr_month_pickup_Count, bcs_SH_source_curr_month_pickup_Count, bcs_CT_source_curr_month_pickup_Count, bcs_DR_source_curr_month_pickup_4HR_Count, bcs_DR_source_curr_month_pickup_8HR_Count, bcs_DR_source_curr_month_pickup_12HR_Count, bcs_AP_source_curr_month_pickup_Count, bcs_OW_source_prev_3month_pickup_Count, bcs_RT_source_prev_3month_pickup_Count, bcs_AT_source_prev_3month_pickup_Count, bcs_PT_source_prev_3month_pickup_Count, bcs_FL_source_prev_3month_pickup_Count, bcs_SH_source_prev_3month_pickup_Count, bcs_CT_source_prev_3month_pickup_Count, bcs_DR_source_prev_3month_pickup_4HR_Count, bcs_DR_source_prev_3month_pickup_8HR_Count, bcs_DR_source_prev_3month_pickup_12HR_Count, bcs_AP_source_prev_3month_pickup_Count, bcs_OW_source_curr_month_create_Count, bcs_RT_source_curr_month_create_Count, bcs_AT_source_curr_month_create_Count, bcs_PT_source_curr_month_create_Count, bcs_FL_source_curr_month_create_Count, bcs_SH_source_curr_month_create_Count, bcs_CT_source_curr_month_create_Count, bcs_DR_source_curr_month_create_4HR_Count, bcs_DR_source_curr_month_create_8HR_Count, bcs_DR_source_curr_month_create_12HR_Count, bcs_AP_source_curr_month_create_Count, bcs_OW_source_prev_3month_create_Count, bcs_RT_source_prev_3month_create_Count, bcs_AT_source_prev_3month_create_Count, bcs_PT_source_prev_3month_create_Count, bcs_FL_source_prev_3month_create_Count, bcs_SH_source_prev_3month_create_Count, bcs_CT_source_prev_3month_create_Count, bcs_DR_source_prev_3month_create_4HR_Count, bcs_DR_source_prev_3month_create_8HR_Count, bcs_DR_source_prev_3month_create_12HR_Count, bcs_AP_source_prev_3month_create_Count, bcs_last_bkg_src_complete_date, bcs_last_bkg_src_create_date, bcs_last_bkg_dst_complete_date, bcs_last_bkg_dst_create_date, bcs_create_date, bcs_modified_date, bcs_active,bcs_OW_dest_curr_month_pickup_Count, bcs_RT_dest_curr_month_pickup_Count, bcs_AT_dest_curr_month_pickup_Count, bcs_PT_dest_curr_month_pickup_Count, bcs_FL_dest_curr_month_pickup_Count, bcs_SH_dest_curr_month_pickup_Count, bcs_CT_dest_curr_month_pickup_Count, bcs_DR_dest_curr_month_pickup_4HR_Count, bcs_DR_dest_curr_month_pickup_8HR_Count, bcs_DR_dest_curr_month_pickup_12HR_Count, bcs_AP_dest_curr_month_pickup_Count, bcs_OW_dest_prev_3month_pickup_Count, bcs_RT_dest_prev_3month_pickup_Count, bcs_AT_dest_prev_3month_pickup_Count, bcs_PT_dest_prev_3month_pickup_Count, bcs_FL_dest_prev_3month_pickup_Count, bcs_SH_dest_prev_3month_pickup_Count, bcs_CT_dest_prev_3month_pickup_Count, bcs_DR_dest_prev_3month_pickup_4HR_Count, bcs_DR_dest_prev_3month_pickup_8HR_Count, bcs_DR_dest_prev_3month_pickup_12HR_Count, bcs_AP_dest_prev_3month_pickup_Count, bcs_OW_dest_curr_month_create_Count, bcs_RT_dest_curr_month_create_Count, bcs_AT_dest_curr_month_create_Count, bcs_PT_dest_curr_month_create_Count, bcs_FL_dest_curr_month_create_Count, bcs_SH_dest_curr_month_create_Count, bcs_CT_dest_curr_month_create_Count, bcs_DR_dest_curr_month_create_4HR_Count, bcs_DR_dest_curr_month_create_8HR_Count, bcs_DR_dest_curr_month_create_12HR_Count, bcs_AP_dest_curr_month_create_Count, bcs_OW_dest_prev_3month_create_Count, bcs_RT_dest_prev_3month_create_Count, bcs_AT_dest_prev_3month_create_Count, bcs_PT_dest_prev_3month_create_Count, bcs_FL_dest_prev_3month_create_Count, bcs_SH_dest_prev_3month_create_Count, bcs_CT_dest_prev_3month_create_Count, bcs_DR_dest_prev_3month_create_4HR_Count, bcs_DR_dest_prev_3month_create_8HR_Count, bcs_DR_dest_prev_3month_create_12HR_Count, bcs_AP_dest_prev_3month_create_Count', 'safe', 'on' => 'search'),
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
            'bcs_id'                                      => 'Bcs',
            'bcs_cty_id'                                  => 'Bcs Cty',
            'bcs_source_Count'                            => 'Bcs Source Count',
            'bcs_dest_Count'                              => 'Bcs Dest Count',
            'bcs_OW_source_curr_month_pickup_Count'       => 'Bcs Ow Source Curr Month Pickup Count',
            'bcs_RT_source_curr_month_pickup_Count'       => 'Bcs Rt Source Curr Month Pickup Count',
            'bcs_AT_source_curr_month_pickup_Count'       => 'Bcs At Source Curr Month Pickup Count',
            'bcs_PT_source_curr_month_pickup_Count'       => 'Bcs Pt Source Curr Month Pickup Count',
            'bcs_FL_source_curr_month_pickup_Count'       => 'Bcs Fl Source Curr Month Pickup Count',
            'bcs_SH_source_curr_month_pickup_Count'       => 'Bcs Sh Source Curr Month Pickup Count',
            'bcs_CT_source_curr_month_pickup_Count'       => 'Bcs Ct Source Curr Month Pickup Count',
            'bcs_DR_source_curr_month_pickup_4HR_Count'   => 'Bcs Dr Source Curr Month Pickup 4 Hr Count',
            'bcs_DR_source_curr_month_pickup_8HR_Count'   => 'Bcs Dr Source Curr Month Pickup 8 Hr Count',
            'bcs_DR_source_curr_month_pickup_12HR_Count'  => 'Bcs Dr Source Curr Month Pickup 12 Hr Count',
            'bcs_AP_source_curr_month_pickup_Count'       => 'Bcs Ap Source Curr Month Pickup Count',
            'bcs_OW_source_prev_3month_pickup_Count'      => 'Bcs Ow Source Prev 3month Pickup Count',
            'bcs_RT_source_prev_3month_pickup_Count'      => 'Bcs Rt Source Prev 3month Pickup Count',
            'bcs_AT_source_prev_3month_pickup_Count'      => 'Bcs At Source Prev 3month Pickup Count',
            'bcs_PT_source_prev_3month_pickup_Count'      => 'Bcs Pt Source Prev 3month Pickup Count',
            'bcs_FL_source_prev_3month_pickup_Count'      => 'Bcs Fl Source Prev 3month Pickup Count',
            'bcs_SH_source_prev_3month_pickup_Count'      => 'Bcs Sh Source Prev 3month Pickup Count',
            'bcs_CT_source_prev_3month_pickup_Count'      => 'Bcs Ct Source Prev 3month Pickup Count',
            'bcs_DR_source_prev_3month_pickup_4HR_Count'  => 'Bcs Dr Source Prev 3month Pickup 4 Hr Count',
            'bcs_DR_source_prev_3month_pickup_8HR_Count'  => 'Bcs Dr Source Prev 3month Pickup 8 Hr Count',
            'bcs_DR_source_prev_3month_pickup_12HR_Count' => 'Bcs Dr Source Prev 3month Pickup 12 Hr Count',
            'bcs_AP_source_prev_3month_pickup_Count'      => 'Bcs Ap Source Prev 3month Pickup Count',
            'bcs_OW_source_curr_month_create_Count'       => 'Bcs Ow Source Curr Month Create Count',
            'bcs_RT_source_curr_month_create_Count'       => 'Bcs Rt Source Curr Month Create Count',
            'bcs_AT_source_curr_month_create_Count'       => 'Bcs At Source Curr Month Create Count',
            'bcs_PT_source_curr_month_create_Count'       => 'Bcs Pt Source Curr Month Create Count',
            'bcs_FL_source_curr_month_create_Count'       => 'Bcs Fl Source Curr Month Create Count',
            'bcs_SH_source_curr_month_create_Count'       => 'Bcs Sh Source Curr Month Create Count',
            'bcs_CT_source_curr_month_create_Count'       => 'Bcs Ct Source Curr Month Create Count',
            'bcs_DR_source_curr_month_create_4HR_Count'   => 'Bcs Dr Source Curr Month Create 4 Hr Count',
            'bcs_DR_source_curr_month_create_8HR_Count'   => 'Bcs Dr Source Curr Month Create 8 Hr Count',
            'bcs_DR_source_curr_month_create_12HR_Count'  => 'Bcs Dr Source Curr Month Create 12 Hr Count',
            'bcs_AP_source_curr_month_create_Count'       => 'Bcs Ap Source Curr Month Create Count',
            'bcs_OW_source_prev_3month_create_Count'      => 'Bcs Ow Source Prev 3month Create Count',
            'bcs_RT_source_prev_3month_create_Count'      => 'Bcs Rt Source Prev 3month Create Count',
            'bcs_AT_source_prev_3month_create_Count'      => 'Bcs At Source Prev 3month Create Count',
            'bcs_PT_source_prev_3month_create_Count'      => 'Bcs Pt Source Prev 3month Create Count',
            'bcs_FL_source_prev_3month_create_Count'      => 'Bcs Fl Source Prev 3month Create Count',
            'bcs_SH_source_prev_3month_create_Count'      => 'Bcs Sh Source Prev 3month Create Count',
            'bcs_CT_source_prev_3month_create_Count'      => 'Bcs Ct Source Prev 3month Create Count',
            'bcs_DR_source_prev_3month_create_4HR_Count'  => 'Bcs Dr Source Prev 3month Create 4 Hr Count',
            'bcs_DR_source_prev_3month_create_8HR_Count'  => 'Bcs Dr Source Prev 3month Create 8 Hr Count',
            'bcs_DR_source_prev_3month_create_12HR_Count' => 'Bcs Dr Source Prev 3month Create 12 Hr Count',
            'bcs_AP_source_prev_3month_create_Count'      => 'Bcs Ap Source Prev 3month Create Count',
            'bcs_last_bkg_src_complete_date'              => 'Bcs Last Bkg Src Complete Date',
            'bcs_last_bkg_src_create_date'                => 'Bcs Last Bkg Src Create Date',
            'bcs_last_bkg_dst_complete_date'              => 'Bcs Last Bkg Dst Complete Date',
            'bcs_last_bkg_dst_create_date'                => 'Bcs Last Bkg Dst Create Date',
            'bcs_create_date'                             => 'Bcs Create Date',
            'bcs_modified_date'                           => 'Bcs Modified Date',
            'bcs_active'                                  => 'Bcs Active',
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

        $criteria->compare('bcs_id', $this->bcs_id);
        $criteria->compare('bcs_cty_id', $this->bcs_cty_id);
        $criteria->compare('bcs_source_Count', $this->bcs_source_Count);
        $criteria->compare('bcs_dest_Count', $this->bcs_dest_Count);
        $criteria->compare('bcs_OW_source_curr_month_pickup_Count', $this->bcs_OW_source_curr_month_pickup_Count);
        $criteria->compare('bcs_RT_source_curr_month_pickup_Count', $this->bcs_RT_source_curr_month_pickup_Count);
        $criteria->compare('bcs_AT_source_curr_month_pickup_Count', $this->bcs_AT_source_curr_month_pickup_Count);
        $criteria->compare('bcs_PT_source_curr_month_pickup_Count', $this->bcs_PT_source_curr_month_pickup_Count);
        $criteria->compare('bcs_FL_source_curr_month_pickup_Count', $this->bcs_FL_source_curr_month_pickup_Count);
        $criteria->compare('bcs_SH_source_curr_month_pickup_Count', $this->bcs_SH_source_curr_month_pickup_Count);
        $criteria->compare('bcs_CT_source_curr_month_pickup_Count', $this->bcs_CT_source_curr_month_pickup_Count);
        $criteria->compare('bcs_DR_source_curr_month_pickup_4HR_Count', $this->bcs_DR_source_curr_month_pickup_4HR_Count);
        $criteria->compare('bcs_DR_source_curr_month_pickup_8HR_Count', $this->bcs_DR_source_curr_month_pickup_8HR_Count);
        $criteria->compare('bcs_DR_source_curr_month_pickup_12HR_Count', $this->bcs_DR_source_curr_month_pickup_12HR_Count);
        $criteria->compare('bcs_AP_source_curr_month_pickup_Count', $this->bcs_AP_source_curr_month_pickup_Count);
        $criteria->compare('bcs_OW_source_prev_3month_pickup_Count', $this->bcs_OW_source_prev_3month_pickup_Count);
        $criteria->compare('bcs_RT_source_prev_3month_pickup_Count', $this->bcs_RT_source_prev_3month_pickup_Count);
        $criteria->compare('bcs_AT_source_prev_3month_pickup_Count', $this->bcs_AT_source_prev_3month_pickup_Count);
        $criteria->compare('bcs_PT_source_prev_3month_pickup_Count', $this->bcs_PT_source_prev_3month_pickup_Count);
        $criteria->compare('bcs_FL_source_prev_3month_pickup_Count', $this->bcs_FL_source_prev_3month_pickup_Count);
        $criteria->compare('bcs_SH_source_prev_3month_pickup_Count', $this->bcs_SH_source_prev_3month_pickup_Count);
        $criteria->compare('bcs_CT_source_prev_3month_pickup_Count', $this->bcs_CT_source_prev_3month_pickup_Count);
        $criteria->compare('bcs_DR_source_prev_3month_pickup_4HR_Count', $this->bcs_DR_source_prev_3month_pickup_4HR_Count);
        $criteria->compare('bcs_DR_source_prev_3month_pickup_8HR_Count', $this->bcs_DR_source_prev_3month_pickup_8HR_Count);
        $criteria->compare('bcs_DR_source_prev_3month_pickup_12HR_Count', $this->bcs_DR_source_prev_3month_pickup_12HR_Count);
        $criteria->compare('bcs_AP_source_prev_3month_pickup_Count', $this->bcs_AP_source_prev_3month_pickup_Count);
        $criteria->compare('bcs_OW_source_curr_month_create_Count', $this->bcs_OW_source_curr_month_create_Count);
        $criteria->compare('bcs_RT_source_curr_month_create_Count', $this->bcs_RT_source_curr_month_create_Count);
        $criteria->compare('bcs_AT_source_curr_month_create_Count', $this->bcs_AT_source_curr_month_create_Count);
        $criteria->compare('bcs_PT_source_curr_month_create_Count', $this->bcs_PT_source_curr_month_create_Count);
        $criteria->compare('bcs_FL_source_curr_month_create_Count', $this->bcs_FL_source_curr_month_create_Count);
        $criteria->compare('bcs_SH_source_curr_month_create_Count', $this->bcs_SH_source_curr_month_create_Count);
        $criteria->compare('bcs_CT_source_curr_month_create_Count', $this->bcs_CT_source_curr_month_create_Count);
        $criteria->compare('bcs_DR_source_curr_month_create_4HR_Count', $this->bcs_DR_source_curr_month_create_4HR_Count);
        $criteria->compare('bcs_DR_source_curr_month_create_8HR_Count', $this->bcs_DR_source_curr_month_create_8HR_Count);
        $criteria->compare('bcs_DR_source_curr_month_create_12HR_Count', $this->bcs_DR_source_curr_month_create_12HR_Count);
        $criteria->compare('bcs_AP_source_curr_month_create_Count', $this->bcs_AP_source_curr_month_create_Count);
        $criteria->compare('bcs_OW_source_prev_3month_create_Count', $this->bcs_OW_source_prev_3month_create_Count);
        $criteria->compare('bcs_RT_source_prev_3month_create_Count', $this->bcs_RT_source_prev_3month_create_Count);
        $criteria->compare('bcs_AT_source_prev_3month_create_Count', $this->bcs_AT_source_prev_3month_create_Count);
        $criteria->compare('bcs_PT_source_prev_3month_create_Count', $this->bcs_PT_source_prev_3month_create_Count);
        $criteria->compare('bcs_FL_source_prev_3month_create_Count', $this->bcs_FL_source_prev_3month_create_Count);
        $criteria->compare('bcs_SH_source_prev_3month_create_Count', $this->bcs_SH_source_prev_3month_create_Count);
        $criteria->compare('bcs_CT_source_prev_3month_create_Count', $this->bcs_CT_source_prev_3month_create_Count);
        $criteria->compare('bcs_DR_source_prev_3month_create_4HR_Count', $this->bcs_DR_source_prev_3month_create_4HR_Count);
        $criteria->compare('bcs_DR_source_prev_3month_create_8HR_Count', $this->bcs_DR_source_prev_3month_create_8HR_Count);
        $criteria->compare('bcs_DR_source_prev_3month_create_12HR_Count', $this->bcs_DR_source_prev_3month_create_12HR_Count);
        $criteria->compare('bcs_AP_source_prev_3month_create_Count', $this->bcs_AP_source_prev_3month_create_Count);
        $criteria->compare('bcs_last_bkg_src_complete_date', $this->bcs_last_bkg_src_complete_date, true);
        $criteria->compare('bcs_last_bkg_src_create_date', $this->bcs_last_bkg_src_create_date, true);
        $criteria->compare('bcs_last_bkg_dst_complete_date', $this->bcs_last_bkg_dst_complete_date, true);
        $criteria->compare('bcs_last_bkg_dst_create_date', $this->bcs_last_bkg_dst_create_date, true);
        $criteria->compare('bcs_create_date', $this->bcs_create_date, true);
        $criteria->compare('bcs_modified_date', $this->bcs_modified_date, true);
        $criteria->compare('bcs_active', $this->bcs_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BookingCitiesStats the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function getList($cityId = 0)
    {
        $whereTo   = ($cityId > 0) ? " AND bkg_to_city_id=$cityId" : "";
        $whereFrom = ($cityId > 0) ? " AND bkg_from_city_id=$cityId" : "";
        $sql       = "SELECT                    
                    temp.CityId AS bcs_cty_id,
                    SUM(temp.SourceCity) AS bcs_source_Count,
                    SUM(temp.DestinationCity) AS bcs_dest_Count,
                    SUM(OW_source_curr_month_pickup_Count) AS bcs_OW_source_curr_month_pickup_Count,
                    SUM(RT_source_curr_month_pickup_Count) AS bcs_RT_source_curr_month_pickup_Count,
                    SUM(AT_source_curr_month_pickup_Count) AS bcs_AT_source_curr_month_pickup_Count,
                    SUM(PT_source_curr_month_pickup_Count) AS bcs_PT_source_curr_month_pickup_Count,
                    SUM(FL_source_curr_month_pickup_Count) AS bcs_FL_source_curr_month_pickup_Count,
                    SUM(SH_source_curr_month_pickup_Count) AS bcs_SH_source_curr_month_pickup_Count,
                    SUM(CT_source_curr_month_pickup_Count) AS bcs_CT_source_curr_month_pickup_Count,
                    SUM(DR_source_curr_month_pickup_4HR_Count) AS bcs_DR_source_curr_month_pickup_4HR_Count,
                    SUM(DR_8HR_source_curr_month_pickup_Count) AS bcs_DR_8HR_source_curr_month_pickup_Count,
                    SUM(DR_12HR_source_curr_month_pickup_Count) AS bcs_DR_12HR_source_curr_month_pickup_Count,
                    SUM(AP_source_curr_month_pickup_Count) AS bcs_AP_source_curr_month_pickup_Count,
                    SUM(OW_source_prev_3month_pickup_Count) AS bcs_OW_source_prev_3month_pickup_Count,
                    SUM(RT_source_prev_3month_pickup_Count) AS bcs_RT_source_prev_3month_pickup_Count,
                    SUM(AT_source_prev_3month_pickup_Count) AS bcs_AT_source_prev_3month_pickup_Count,
                    SUM(PT_source_prev_3month_pickup_Count) AS bcs_PT_source_prev_3month_pickup_Count,
                    SUM(FL_source_prev_3month_pickup_Count) AS bcs_FL_source_prev_3month_pickup_Count,
                    SUM(SH_source_prev_3month_pickup_Count) AS bcs_SH_source_prev_3month_pickup_Count,
                    SUM(CT_source_prev_3month_pickup_Count) AS bcs_CT_source_prev_3month_pickup_Count,
                    SUM(DR_source_prev_3month_pickup_4HR_Count) AS bcs_DR_source_prev_3month_pickup_4HR_Count,
                    SUM(DR_8HR_source_prev_3month_pickup_Count) AS bcs_DR_8HR_source_prev_3month_pickup_Count,
                    SUM(DR_12HR_source_prev_3month_pickup_Count) AS bcs_DR_12HR_source_prev_3month_pickup_Count,
                    SUM(AP_source_prev_3month_pickup_Count) AS bcs_AP_source_prev_3month_pickup_Count,
                    SUM(OW_source_curr_month_create_Count) AS bcs_OW_source_curr_month_create_Count,
                    SUM(RT_source_curr_month_create_Count) AS bcs_RT_source_curr_month_create_Count,
                    SUM(AT_source_curr_month_create_Count) AS bcs_AT_source_curr_month_create_Count,
                    SUM(PT_source_curr_month_create_Count) AS bcs_PT_source_curr_month_create_Count,
                    SUM(FL_source_curr_month_create_Count) AS bcs_FL_source_curr_month_create_Count,
                    SUM(SH_source_curr_month_create_Count) AS bcs_SH_source_curr_month_create_Count,
                    SUM(CT_source_curr_month_create_Count) AS bcs_CT_source_curr_month_create_Count,
                    SUM(DR_source_curr_month_create_4HR_Count) AS bcs_DR_source_curr_month_create_4HR_Count,
                    SUM(DR_8HR_source_curr_month_create_Count) AS bcs_DR_8HR_source_curr_month_create_Count,
                    SUM(DR_12HR_source_curr_month_create_Count) AS bcs_DR_12HR_source_curr_month_create_Count,
                    SUM(AP_source_curr_month_create_Count) AS bcs_AP_source_curr_month_create_Count,
                    SUM(OW_source_prev_3month_create_Count) AS bcs_OW_source_prev_3month_create_Count,
                    SUM(RT_source_prev_3month_create_Count) AS bcs_RT_source_prev_3month_create_Count,
                    SUM(AT_source_prev_3month_create_Count) AS bcs_AT_source_prev_3month_create_Count,
                    SUM(PT_source_prev_3month_create_Count) AS bcs_PT_source_prev_3month_create_Count,
                    SUM(FL_source_prev_3month_create_Count) AS bcs_FL_source_prev_3month_create_Count,
                    SUM(SH_source_prev_3month_create_Count) AS bcs_SH_source_prev_3month_create_Count,
                    SUM(CT_source_prev_3month_create_Count) AS bcs_CT_source_prev_3month_create_Count,
                    SUM(DR_source_prev_3month_create_4HR_Count) AS bcs_DR_source_prev_3month_create_4HR_Count,
                    SUM(DR_8HR_source_prev_3month_create_Count) AS bcs_DR_8HR_source_prev_3month_create_Count,
                    SUM(DR_12HR_source_prev_3month_create_Count) AS bcs_DR_12HR_source_prev_3month_create_Count,
                    SUM(AP_source_prev_3month_create_Count) AS bcs_AP_source_prev_3month_create_Count,
                    SUM(OW_dest_curr_month_pickup_Count) AS bcs_OW_dest_curr_month_pickup_Count,
                    SUM(RT_dest_curr_month_pickup_Count) AS bcs_RT_dest_curr_month_pickup_Count,
                    SUM(AT_dest_curr_month_pickup_Count) AS bcs_AT_dest_curr_month_pickup_Count,
                    SUM(PT_dest_curr_month_pickup_Count) AS bcs_PT_dest_curr_month_pickup_Count,
                    SUM(FL_dest_curr_month_pickup_Count) AS bcs_FL_dest_curr_month_pickup_Count,
                    SUM(SH_dest_curr_month_pickup_Count) AS bcs_SH_dest_curr_month_pickup_Count,
                    SUM(CT_dest_curr_month_pickup_Count) AS bcs_CT_dest_curr_month_pickup_Count,
                    SUM(DR_dest_curr_month_pickup_4HR_Count) AS bcs_DR_dest_curr_month_pickup_4HR_Count,
                    SUM(DR_8HR_dest_curr_month_pickup_Count) AS bcs_DR_8HR_dest_curr_month_pickup_Count,
                    SUM(DR_12HR_dest_curr_month_pickup_Count) AS bcs_DR_12HR_dest_curr_month_pickup_Count,
                    SUM(AP_dest_curr_month_pickup_Count) AS bcs_AP_dest_curr_month_pickup_Count,
                    SUM(OW_dest_prev_3month_pickup_Count) AS bcs_OW_dest_prev_3month_pickup_Count,
                    SUM(RT_dest_prev_3month_pickup_Count) AS bcs_RT_dest_prev_3month_pickup_Count,
                    SUM(AT_dest_prev_3month_pickup_Count) AS bcs_AT_dest_prev_3month_pickup_Count,
                    SUM(PT_dest_prev_3month_pickup_Count) AS bcs_PT_dest_prev_3month_pickup_Count,
                    SUM(FL_dest_prev_3month_pickup_Count) AS bcs_FL_dest_prev_3month_pickup_Count,
                    SUM(SH_dest_prev_3month_pickup_Count) AS bcs_SH_dest_prev_3month_pickup_Count,
                    SUM(CT_dest_prev_3month_pickup_Count) AS bcs_CT_dest_prev_3month_pickup_Count,
                    SUM(DR_dest_prev_3month_pickup_4HR_Count) AS bcs_DR_dest_prev_3month_pickup_4HR_Count,
                    SUM(DR_8HR_dest_prev_3month_pickup_Count) AS bcs_DR_8HR_dest_prev_3month_pickup_Count,
                    SUM(DR_12HR_dest_prev_3month_pickup_Count) AS bcs_DR_12HR_dest_prev_3month_pickup_Count,
                    SUM(AP_dest_prev_3month_pickup_Count) AS bcs_AP_dest_prev_3month_pickup_Count,
                    SUM(OW_dest_curr_month_create_Count) AS bcs_OW_dest_curr_month_create_Count,
                    SUM(RT_dest_curr_month_create_Count) AS bcs_RT_dest_curr_month_create_Count,
                    SUM(AT_dest_curr_month_create_Count) AS bcs_AT_dest_curr_month_create_Count,
                    SUM(PT_dest_curr_month_create_Count) AS bcs_PT_dest_curr_month_create_Count,
                    SUM(FL_dest_curr_month_create_Count) AS bcs_FL_dest_curr_month_create_Count,
                    SUM(SH_dest_curr_month_create_Count) AS bcs_SH_dest_curr_month_create_Count,
                    SUM(CT_dest_curr_month_create_Count) AS bcs_CT_dest_curr_month_create_Count,
                    SUM(DR_dest_curr_month_create_4HR_Count) AS bcs_DR_dest_curr_month_create_4HR_Count,
                    SUM(DR_8HR_dest_curr_month_create_Count) AS bcs_DR_8HR_dest_curr_month_create_Count,
                    SUM(DR_12HR_dest_curr_month_create_Count) AS bcs_DR_12HR_dest_curr_month_create_Count,
                    SUM(AP_dest_curr_month_create_Count) AS bcs_AP_dest_curr_month_create_Count,
                    SUM(OW_dest_prev_3month_create_Count) AS bcs_OW_dest_prev_3month_create_Count,
                    SUM(RT_dest_prev_3month_create_Count) AS bcs_RT_dest_prev_3month_create_Count,
                    SUM(AT_dest_prev_3month_create_Count) AS bcs_AT_dest_prev_3month_create_Count,
                    SUM(PT_dest_prev_3month_create_Count) AS bcs_PT_dest_prev_3month_create_Count,
                    SUM(FL_dest_prev_3month_create_Count) AS bcs_FL_dest_prev_3month_create_Count,
                    SUM(SH_dest_prev_3month_create_Count) AS bcs_SH_dest_prev_3month_create_Count,
                    SUM(CT_dest_prev_3month_create_Count) AS bcs_CT_dest_prev_3month_create_Count,
                    SUM(DR_dest_prev_3month_create_4HR_Count) AS bcs_DR_dest_prev_3month_create_4HR_Count,
                    SUM(DR_8HR_dest_prev_3month_create_Count) AS bcs_DR_8HR_dest_prev_3month_create_Count,
                    SUM(DR_12HR_dest_prev_3month_create_Count) AS bcs_DR_12HR_dest_prev_3month_create_Count,
                    SUM(AP_dest_prev_3month_create_Count) AS bcs_AP_dest_prev_3month_create_Count,
                    MAX(last_bkg_src_complete_date) AS bcs_last_bkg_src_complete_date,
                    MAX(last_bkg_src_create_date) AS bcs_last_bkg_src_create_date,
                    MAX(last_bkg_dst_complete_date) AS bcs_last_bkg_dst_complete_date,
                    MAX(last_bkg_dst_create_date) AS  bcs_last_bkg_dst_create_date

                    FROM 
                    (
                        SELECT                        
                        booking.bkg_from_city_id AS CityId,
                        COUNT(bkg_from_city_id)  AS SourceCity,
                        0 AS  DestinationCity,

                        SUM(IF(bkg_booking_type=1 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()) ,1,0)) AS OW_source_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type IN (2,3) AND MONTH(bkg_pickup_date) = MONTH(CURDATE()) ,1,0)) AS RT_source_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=4 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()) ,1,0)) AS AT_source_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=5 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()) ,1,0)) AS PT_source_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=6 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()) ,1,0)) AS FL_source_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=7 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()),1,0)) AS SH_source_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=8 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()),1,0)) AS CT_source_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=9 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()),1,0)) AS DR_source_curr_month_pickup_4HR_Count,
                        SUM(IF(bkg_booking_type=10 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()),1,0)) AS DR_8HR_source_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=11 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()),1,0)) AS DR_12HR_source_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=12 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()),1,0)) AS AP_source_curr_month_pickup_Count,

                        SUM(IF(bkg_booking_type=1 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS OW_source_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type IN (2,3) AND bkg_pickup_date  BETWEEN DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS RT_source_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=4 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS AT_source_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=5 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS PT_source_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=6 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS FL_source_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=7 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS SH_source_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=8 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS CT_source_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=9 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS DR_source_prev_3month_pickup_4HR_Count,
                        SUM(IF(bkg_booking_type=10 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS DR_8HR_source_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=11 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS DR_12HR_source_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=12 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS AP_source_prev_3month_pickup_Count,

                        SUM(IF(bkg_booking_type=1 AND MONTH(bkg_create_date) = MONTH(CURDATE()) ,1,0)) AS OW_source_curr_month_create_Count,
                        SUM(IF(bkg_booking_type IN (2,3) AND MONTH(bkg_create_date) = MONTH(CURDATE()) ,1,0)) AS RT_source_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=4 AND MONTH(bkg_create_date) = MONTH(CURDATE()) ,1,0)) AS AT_source_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=5 AND MONTH(bkg_create_date) = MONTH(CURDATE()) ,1,0)) AS PT_source_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=6 AND MONTH(bkg_create_date) = MONTH(CURDATE()) ,1,0)) AS FL_source_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=7 AND MONTH(bkg_create_date) = MONTH(CURDATE()),1,0)) AS SH_source_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=8 AND MONTH(bkg_create_date) = MONTH(CURDATE()),1,0)) AS CT_source_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=9 AND MONTH(bkg_create_date) = MONTH(CURDATE()),1,0)) AS DR_source_curr_month_create_4HR_Count,
                        SUM(IF(bkg_booking_type=10 AND MONTH(bkg_create_date) = MONTH(CURDATE()),1,0)) AS DR_8HR_source_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=11 AND MONTH(bkg_create_date) = MONTH(CURDATE()),1,0)) AS DR_12HR_source_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=12 AND MONTH(bkg_create_date) = MONTH(CURDATE()),1,0)) AS AP_source_curr_month_create_Count,

                        SUM(IF(bkg_booking_type=1 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS OW_source_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type IN (2,3) AND bkg_create_date  BETWEEN DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS RT_source_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=4 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS AT_source_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=5 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS PT_source_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=6 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS FL_source_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=7 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS SH_source_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=8 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS CT_source_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=9 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS DR_source_prev_3month_create_4HR_Count,
                        SUM(IF(bkg_booking_type=10 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS DR_8HR_source_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=11 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS DR_12HR_source_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=12 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS AP_source_prev_3month_create_Count ,




                        0 AS OW_dest_curr_month_pickup_Count,
                        0 AS RT_dest_curr_month_pickup_Count,
                        0 AS AT_dest_curr_month_pickup_Count,
                        0 AS PT_dest_curr_month_pickup_Count,
                        0 AS FL_dest_curr_month_pickup_Count,
                        0 AS SH_dest_curr_month_pickup_Count,
                        0 AS CT_dest_curr_month_pickup_Count,
                        0 AS DR_dest_curr_month_pickup_4HR_Count,
                        0 AS DR_8HR_dest_curr_month_pickup_Count,
                        0 AS DR_12HR_dest_curr_month_pickup_Count,
                        0 AS AP_dest_curr_month_pickup_Count,

                        0 AS OW_dest_prev_3month_pickup_Count,
                        0 AS RT_dest_prev_3month_pickup_Count,
                        0 AS AT_dest_prev_3month_pickup_Count,
                        0 AS PT_dest_prev_3month_pickup_Count,
                        0 AS FL_dest_prev_3month_pickup_Count,
                        0 AS SH_dest_prev_3month_pickup_Count,
                        0 AS CT_dest_prev_3month_pickup_Count,
                        0 AS DR_dest_prev_3month_pickup_4HR_Count,
                        0 AS DR_8HR_dest_prev_3month_pickup_Count,
                        0 AS DR_12HR_dest_prev_3month_pickup_Count,
                        0 AS AP_dest_prev_3month_pickup_Count,

                        0 AS OW_dest_curr_month_create_Count,
                        0 AS RT_dest_curr_month_create_Count,
                        0 AS AT_dest_curr_month_create_Count,
                        0 AS PT_dest_curr_month_create_Count,
                        0 AS FL_dest_curr_month_create_Count,
                        0 AS SH_dest_curr_month_create_Count,
                        0 AS CT_dest_curr_month_create_Count,
                        0 AS DR_dest_curr_month_create_4HR_Count,
                        0 AS DR_8HR_dest_curr_month_create_Count,
                        0 AS DR_12HR_dest_curr_month_create_Count,
                        0 AS AP_dest_curr_month_create_Count,

                        0 AS OW_dest_prev_3month_create_Count,
                        0 AS RT_dest_prev_3month_create_Count,
                        0 AS AT_dest_prev_3month_create_Count,
                        0 AS PT_dest_prev_3month_create_Count,
                        0 AS FL_dest_prev_3month_create_Count,
                        0 AS SH_dest_prev_3month_create_Count,
                        0 AS CT_dest_prev_3month_create_Count,
                        0 AS DR_dest_prev_3month_create_4HR_Count,
                        0 AS DR_8HR_dest_prev_3month_create_Count,
                        0 AS DR_12HR_dest_prev_3month_create_Count,
                        0 AS AP_dest_prev_3month_create_Count,

                        MAX(bkg_pickup_date) AS last_bkg_src_complete_date,
                        MAX(bkg_create_date) AS last_bkg_src_create_date,
                        '' AS last_bkg_dst_complete_date,
                        '' AS last_bkg_dst_create_date

                        FROM booking                        
                        WHERE 1 
                        $whereFrom
                        AND  bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59')
                        AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59')
                        GROUP BY  bkg_from_city_id

                        UNION 

                        SELECT                         
                        booking.bkg_to_city_id AS  CityId,
                        0  AS SourceCity,
                        COUNT(bkg_to_city_id)  AS DestinationCity,

                         0 AS OW_create_curr_month_pickup_Count,
                        0 AS RT_create_curr_month_pickup_Count,
                        0 AS AT_create_curr_month_pickup_Count,
                        0 AS PT_create_curr_month_pickup_Count,
                        0 AS FL_create_curr_month_pickup_Count,
                        0 AS SH_create_curr_month_pickup_Count,
                        0 AS CT_create_curr_month_pickup_Count,
                        0 AS DR_create_curr_month_pickup_4HR_Count,
                        0 AS DR_8HR_create_curr_month_pickup_Count,
                        0 AS DR_12HR_create_curr_month_pickup_Count,
                        0 AS AP_create_curr_month_pickup_Count,

                        0 AS OW_create_prev_3month_pickup_Count,
                        0 AS RT_create_prev_3month_pickup_Count,
                        0 AS AT_create_prev_3month_pickup_Count,
                        0 AS PT_create_prev_3month_pickup_Count,
                        0 AS FL_create_prev_3month_pickup_Count,
                        0 AS SH_create_prev_3month_pickup_Count,
                        0 AS CT_create_prev_3month_pickup_Count,
                        0 AS DR_create_prev_3month_pickup_4HR_Count,
                        0 AS DR_8HR_create_prev_3month_pickup_Count,
                        0 AS DR_12HR_create_prev_3month_pickup_Count,
                        0 AS AP_create_prev_3month_pickup_Count,

                        0 AS OW_create_curr_month_create_Count,
                        0 AS RT_create_curr_month_create_Count,
                        0 AS AT_create_curr_month_create_Count,
                        0 AS PT_create_curr_month_create_Count,
                        0 AS FL_create_curr_month_create_Count,
                        0 AS SH_create_curr_month_create_Count,
                        0 AS CT_create_curr_month_create_Count,
                        0 AS DR_create_curr_month_create_4HR_Count,
                        0 AS DR_8HR_create_curr_month_create_Count,
                        0 AS DR_12HR_create_curr_month_create_Count,
                        0 AS AP_create_curr_month_create_Count,

                        0 AS OW_create_prev_3month_create_Count,
                        0 AS RT_create_prev_3month_create_Count,
                        0 AS AT_create_prev_3month_create_Count,
                        0 AS PT_create_prev_3month_create_Count,
                        0 AS FL_create_prev_3month_create_Count,
                        0 AS SH_create_prev_3month_create_Count,
                        0 AS CT_create_prev_3month_create_Count,
                        0 AS DR_create_prev_3month_create_4HR_Count,
                        0 AS DR_8HR_create_prev_3month_create_Count,
                        0 AS DR_12HR_create_prev_3month_create_Count,
                        0 AS AP_create_prev_3month_create_Count ,

                        SUM(IF(bkg_booking_type=1 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()) ,1,0)) AS OW_dest_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type IN (2,3) AND MONTH(bkg_pickup_date) = MONTH(CURDATE()) ,1,0)) AS RT_dest_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=4 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()) ,1,0)) AS AT_dest_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=5 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()) ,1,0)) AS PT_dest_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=6 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()) ,1,0)) AS FL_dest_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=7 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()),1,0)) AS SH_dest_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=8 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()),1,0)) AS CT_dest_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=9 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()),1,0)) AS DR_dest_curr_month_pickup_4HR_Count,
                        SUM(IF(bkg_booking_type=10 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()),1,0)) AS DR_8HR_dest_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=11 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()),1,0)) AS DR_12HR_dest_curr_month_pickup_Count,
                        SUM(IF(bkg_booking_type=12 AND MONTH(bkg_pickup_date) = MONTH(CURDATE()),1,0)) AS AP_dest_curr_month_pickup_Count,

                        SUM(IF(bkg_booking_type=1 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS OW_dest_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type IN (2,3) AND bkg_pickup_date  BETWEEN DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS RT_dest_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=4 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS AT_dest_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=5 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS PT_dest_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=6 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS FL_dest_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=7 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS SH_dest_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=8 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS CT_dest_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=9 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS DR_dest_prev_3month_pickup_4HR_Count,
                        SUM(IF(bkg_booking_type=10 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS DR_8HR_dest_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=11 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS DR_12HR_dest_prev_3month_pickup_Count,
                        SUM(IF(bkg_booking_type=12 AND bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS AP_dest_prev_3month_pickup_Count,

                        SUM(IF(bkg_booking_type=1 AND MONTH(bkg_create_date) = MONTH(CURDATE()) ,1,0)) AS OW_dest_curr_month_create_Count,
                        SUM(IF(bkg_booking_type IN (2,3) AND MONTH(bkg_create_date) = MONTH(CURDATE()) ,1,0)) AS RT_dest_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=4 AND MONTH(bkg_create_date) = MONTH(CURDATE()) ,1,0)) AS AT_dest_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=5 AND MONTH(bkg_create_date) = MONTH(CURDATE()) ,1,0)) AS PT_dest_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=6 AND MONTH(bkg_create_date) = MONTH(CURDATE()) ,1,0)) AS FL_dest_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=7 AND MONTH(bkg_create_date) = MONTH(CURDATE()),1,0)) AS SH_dest_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=8 AND MONTH(bkg_create_date) = MONTH(CURDATE()),1,0)) AS CT_dest_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=9 AND MONTH(bkg_create_date) = MONTH(CURDATE()),1,0)) AS DR_dest_curr_month_create_4HR_Count,
                        SUM(IF(bkg_booking_type=10 AND MONTH(bkg_create_date) = MONTH(CURDATE()),1,0)) AS DR_8HR_dest_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=11 AND MONTH(bkg_create_date) = MONTH(CURDATE()),1,0)) AS DR_12HR_dest_curr_month_create_Count,
                        SUM(IF(bkg_booking_type=12 AND MONTH(bkg_create_date) = MONTH(CURDATE()),1,0)) AS AP_dest_curr_month_create_Count,

                        SUM(IF(bkg_booking_type=1 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS OW_dest_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type IN (2,3) AND bkg_create_date  BETWEEN DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS RT_dest_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=4 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS AT_dest_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=5 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS PT_dest_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=6 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS FL_dest_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=7 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS SH_dest_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=8 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS CT_dest_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=9 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS DR_dest_prev_3month_create_4HR_Count,
                        SUM(IF(bkg_booking_type=10 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS DR_8HR_dest_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=11 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59') ,1,0)) AS DR_12HR_dest_prev_3month_create_Count,
                        SUM(IF(bkg_booking_type=12 AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59'),1,0)) AS AP_dest_prev_3month_create_Count,       
                        '' AS last_bkg_src_complete_date,
                        '' AS last_bkg_src_create_date,
                        MAX(bkg_pickup_date) AS last_bkg_dst_complete_date,
                        MAX(bkg_create_date)  AS last_bkg_dst_create_date
                        FROM booking                       
                        WHERE 1     
                        $whereTo
                        AND  bkg_pickup_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59')
                        AND bkg_create_date  BETWEEN  DATE_SUB(CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00'),INTERVAL 3 MONTH) AND CONCAT(LAST_DAY(NOW()) , ' 23:59:59')
                        GROUP BY bkg_to_city_id
                    ) temp  WHERE 1 AND temp.CityId>0  GROUP BY temp.CityId";
        return DBUtil::query($sql, DBUtil::SDB());
    }

    public function getbyBkgId($cityId)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('bcs_cty_id', $cityId);
        $criteria->compare('bcs_active', 1);
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
     * 
     * @param type booking cities stats Objects
     * @return boolean
     */
    public function updateAttr($modelBookingCitiesStats)
    {
        $success = false;
        $model   = $this->getbyBkgId($modelBookingCitiesStats['bcs_cty_id']);
        if (!$model)
        {
            $model = new BookingCitiesStats();
        }
        $model->bcs_cty_id = $modelBookingCitiesStats['bcs_cty_id'];

        if ($modelBookingCitiesStats['bcs_source_Count'] != '' && $modelBookingCitiesStats['bcs_source_Count'] != NULL)
        {
            $model->bcs_source_Count = $modelBookingCitiesStats['bcs_source_Count'];
        }
        if ($modelBookingCitiesStats['bcs_dest_Count'] != '' && $modelBookingCitiesStats['bcs_dest_Count'] != NULL)
        {
            $model->bcs_dest_Count = $modelBookingCitiesStats['bcs_dest_Count'];
        }
        if ($modelBookingCitiesStats['bcs_OW_source_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_OW_source_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_OW_source_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_OW_source_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_RT_source_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_RT_source_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_RT_source_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_RT_source_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AT_source_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_AT_source_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_AT_source_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_AT_source_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_PT_source_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_PT_source_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_PT_source_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_PT_source_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_FL_source_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_FL_source_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_FL_source_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_FL_source_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_SH_source_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_SH_source_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_SH_source_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_SH_source_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_CT_source_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_CT_source_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_CT_source_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_CT_source_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_source_curr_month_pickup_4HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_source_curr_month_pickup_4HR_Count'] != NULL)
        {
            $model->bcs_DR_source_curr_month_pickup_4HR_Count = $modelBookingCitiesStats['bcs_DR_source_curr_month_pickup_4HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_source_curr_month_pickup_8HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_source_curr_month_pickup_8HR_Count'] != NULL)
        {
            $model->bcs_DR_source_curr_month_pickup_8HR_Count = $modelBookingCitiesStats['bcs_DR_source_curr_month_pickup_8HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_source_curr_month_pickup_12HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_source_curr_month_pickup_12HR_Count'] != NULL)
        {
            $model->bcs_DR_source_curr_month_pickup_12HR_Count = $modelBookingCitiesStats['bcs_DR_source_curr_month_pickup_12HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AP_source_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_AP_source_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_AP_source_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_AP_source_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_OW_source_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_OW_source_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_OW_source_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_OW_source_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_RT_source_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_RT_source_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_RT_source_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_RT_source_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AT_source_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_AT_source_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_AT_source_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_AT_source_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_PT_source_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_PT_source_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_PT_source_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_PT_source_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_FL_source_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_FL_source_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_FL_source_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_FL_source_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_SH_source_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_SH_source_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_SH_source_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_SH_source_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_CT_source_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_CT_source_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_CT_source_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_CT_source_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_source_prev_3month_pickup_4HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_source_prev_3month_pickup_4HR_Count'] != NULL)
        {
            $model->bcs_DR_source_prev_3month_pickup_4HR_Count = $modelBookingCitiesStats['bcs_DR_source_prev_3month_pickup_4HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_source_prev_3month_pickup_8HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_source_prev_3month_pickup_8HR_Count'] != NULL)
        {
            $model->bcs_DR_source_prev_3month_pickup_8HR_Count = $modelBookingCitiesStats['bcs_DR_source_prev_3month_pickup_8HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_source_prev_3month_pickup_12HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_source_prev_3month_pickup_12HR_Count'] != NULL)
        {
            $model->bcs_DR_source_prev_3month_pickup_12HR_Count = $modelBookingCitiesStats['bcs_DR_source_prev_3month_pickup_12HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AP_source_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_AP_source_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_AP_source_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_AP_source_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_OW_source_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_OW_source_curr_month_create_Count'] != NULL)
        {
            $model->bcs_OW_source_curr_month_create_Count = $modelBookingCitiesStats['bcs_OW_source_curr_month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_RT_source_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_RT_source_curr_month_create_Count'] != NULL)
        {
            $model->bcs_RT_source_curr_month_create_Count = $modelBookingCitiesStats['bcs_RT_source_curr_month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AT_source_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_AT_source_curr_month_create_Count'] != NULL)
        {
            $model->bcs_AT_source_curr_month_create_Count = $modelBookingCitiesStats['bcs_AT_source_curr_month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_PT_source_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_PT_source_curr_month_create_Count'] != NULL)
        {
            $model->bcs_PT_source_curr_month_create_Count = $modelBookingCitiesStats['bcs_PT_source_curr_month_create_Count'];
        }

        if ($modelBookingCitiesStats['bcs_FL_source_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_FL_source_curr_month_create_Count'] != NULL)
        {
            $model->bcs_FL_source_curr_month_create_Count = $modelBookingCitiesStats['bcs_FL_source_curr_month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_SH_source_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_SH_source_curr_month_create_Count'] != NULL)
        {
            $model->bcs_SH_source_curr_month_create_Count = $modelBookingCitiesStats['bcs_SH_source_curr_month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_CT_source_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_CT_source_curr_month_create_Count'] != NULL)
        {
            $model->bcs_CT_source_curr_month_create_Count = $modelBookingCitiesStats['bcs_CT_source_curr_month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_source_curr_month_create_4HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_source_curr_month_create_4HR_Count'] != NULL)
        {
            $model->bcs_DR_source_curr_month_create_4HR_Count = $modelBookingCitiesStats['bcs_DR_source_curr_month_create_4HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_source_curr_month_create_8HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_source_curr_month_create_8HR_Count'] != NULL)
        {
            $model->bcs_DR_source_curr_month_create_8HR_Count = $modelBookingCitiesStats['bcs_DR_source_curr_month_create_8HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_source_curr_month_create_12HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_source_curr_month_create_12HR_Count'] != NULL)
        {
            $model->bcs_DR_source_curr_month_create_12HR_Count = $modelBookingCitiesStats['bcs_DR_source_curr_month_create_12HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AP_source_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_AP_source_curr_month_create_Count'] != NULL)
        {
            $model->bcs_AP_source_curr_month_create_Count = $modelBookingCitiesStats['bcs_AP_source_curr_month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_OW_source_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_OW_source_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_OW_source_prev_3month_create_Count = $modelBookingCitiesStats['bcs_OW_source_prev_3month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_RT_source_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_RT_source_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_RT_source_prev_3month_create_Count = $modelBookingCitiesStats['bcs_RT_source_prev_3month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AT_source_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_AT_source_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_AT_source_prev_3month_create_Count = $modelBookingCitiesStats['bcs_AT_source_prev_3month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_PT_source_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_PT_source_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_PT_source_prev_3month_create_Count = $modelBookingCitiesStats['bcs_PT_source_prev_3month_create_Count'];
        }

        if ($modelBookingCitiesStats['bcs_FL_source_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_FL_source_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_FL_source_prev_3month_create_Count = $modelBookingCitiesStats['bcs_FL_source_prev_3month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_SH_source_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_SH_source_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_SH_source_prev_3month_create_Count = $modelBookingCitiesStats['bcs_SH_source_prev_3month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_CT_source_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_CT_source_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_CT_source_prev_3month_create_Count = $modelBookingCitiesStats['bcs_CT_source_prev_3month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_source_prev_3month_create_4HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_source_prev_3month_create_4HR_Count'] != NULL)
        {
            $model->bcs_DR_source_prev_3month_create_4HR_Count = $modelBookingCitiesStats['bcs_DR_source_prev_3month_create_4HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_source_prev_3month_create_8HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_source_prev_3month_create_8HR_Count'] != NULL)
        {
            $model->bcs_DR_source_prev_3month_create_8HR_Count = $modelBookingCitiesStats['bcs_DR_source_prev_3month_create_8HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_source_prev_3month_create_12HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_source_prev_3month_create_12HR_Count'] != NULL)
        {
            $model->bcs_DR_source_prev_3month_create_12HR_Count = $modelBookingCitiesStats['bcs_DR_source_prev_3month_create_12HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AP_source_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_AP_source_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_AP_source_prev_3month_create_Count = $modelBookingCitiesStats['bcs_AP_source_prev_3month_create_Count'];
        }

        if ($modelBookingCitiesStats['bcs_OW_dest_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_OW_dest_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_OW_dest_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_OW_dest_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_RT_dest_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_RT_dest_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_RT_dest_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_RT_dest_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AT_dest_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_AT_dest_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_AT_dest_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_AT_dest_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_PT_dest_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_PT_dest_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_PT_dest_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_PT_dest_curr_month_pickup_Count'];
        }

        if ($modelBookingCitiesStats['bcs_FL_dest_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_FL_dest_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_FL_dest_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_FL_dest_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_SH_dest_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_SH_dest_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_SH_dest_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_SH_dest_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_CT_dest_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_CT_dest_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_CT_dest_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_CT_dest_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_dest_curr_month_pickup_4HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_dest_curr_month_pickup_4HR_Count'] != NULL)
        {
            $model->bcs_DR_dest_curr_month_pickup_4HR_Count = $modelBookingCitiesStats['bcs_DR_dest_curr_month_pickup_4HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_dest_curr_month_pickup_8HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_dest_curr_month_pickup_8HR_Count'] != NULL)
        {
            $model->bcs_DR_dest_curr_month_pickup_8HR_Count = $modelBookingCitiesStats['bcs_DR_dest_curr_month_pickup_8HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_dest_curr_month_pickup_12HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_dest_curr_month_pickup_12HR_Count'] != NULL)
        {
            $model->bcs_DR_dest_curr_month_pickup_12HR_Count = $modelBookingCitiesStats['bcs_DR_dest_curr_month_pickup_12HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AP_dest_curr_month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_AP_dest_curr_month_pickup_Count'] != NULL)
        {
            $model->bcs_AP_dest_curr_month_pickup_Count = $modelBookingCitiesStats['bcs_AP_dest_curr_month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_OW_dest_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_OW_dest_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_OW_dest_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_OW_dest_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_RT_dest_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_RT_dest_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_RT_dest_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_RT_dest_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AT_dest_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_AT_dest_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_AT_dest_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_AT_dest_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_PT_dest_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_PT_dest_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_PT_dest_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_PT_dest_prev_3month_pickup_Count'];
        }

        if ($modelBookingCitiesStats['bcs_FL_dest_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_FL_dest_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_FL_dest_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_FL_dest_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_SH_dest_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_SH_dest_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_SH_dest_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_SH_dest_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_CT_dest_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_CT_dest_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_CT_dest_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_CT_dest_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_dest_prev_3month_pickup_4HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_dest_prev_3month_pickup_4HR_Count'] != NULL)
        {
            $model->bcs_DR_dest_prev_3month_pickup_4HR_Count = $modelBookingCitiesStats['bcs_DR_dest_prev_3month_pickup_4HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_dest_prev_3month_pickup_8HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_dest_prev_3month_pickup_8HR_Count'] != NULL)
        {
            $model->bcs_DR_dest_prev_3month_pickup_8HR_Count = $modelBookingCitiesStats['bcs_DR_dest_prev_3month_pickup_8HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_dest_prev_3month_pickup_12HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_dest_prev_3month_pickup_12HR_Count'] != NULL)
        {
            $model->bcs_DR_dest_prev_3month_pickup_12HR_Count = $modelBookingCitiesStats['bcs_DR_dest_prev_3month_pickup_12HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AP_dest_prev_3month_pickup_Count'] != '' && $modelBookingCitiesStats['bcs_AP_dest_prev_3month_pickup_Count'] != NULL)
        {
            $model->bcs_AP_dest_prev_3month_pickup_Count = $modelBookingCitiesStats['bcs_AP_dest_prev_3month_pickup_Count'];
        }
        if ($modelBookingCitiesStats['bcs_OW_dest_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_OW_dest_curr_month_create_Count'] != NULL)
        {
            $model->bcs_OW_dest_curr_month_create_Count = $modelBookingCitiesStats['bcs_OW_dest_curr_month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_RT_dest_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_RT_dest_curr_month_create_Count'] != NULL)
        {
            $model->bcs_RT_dest_curr_month_create_Count = $modelBookingCitiesStats['bcs_RT_dest_curr_month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AT_dest_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_AT_dest_curr_month_create_Count'] != NULL)
        {
            $model->bcs_AT_dest_curr_month_create_Count = $modelBookingCitiesStats['bcs_AT_dest_curr_month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_PT_dest_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_PT_dest_curr_month_create_Count'] != NULL)
        {
            $model->bcs_PT_dest_curr_month_create_Count = $modelBookingCitiesStats['bcs_PT_dest_curr_month_create_Count'];
        }

        if ($modelBookingCitiesStats['bcs_FL_dest_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_FL_dest_curr_month_create_Count'] != NULL)
        {
            $model->bcs_FL_dest_curr_month_create_Count = $modelBookingCitiesStats['bcs_FL_dest_curr_month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_SH_dest_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_SH_dest_curr_month_create_Count'] != NULL)
        {
            $model->bcs_SH_dest_curr_month_create_Count = $modelBookingCitiesStats['bcs_SH_dest_curr_month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_CT_dest_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_CT_dest_curr_month_create_Count'] != NULL)
        {
            $model->bcs_CT_dest_curr_month_create_Count = $modelBookingCitiesStats['bcs_CT_dest_curr_month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_dest_curr_month_create_4HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_dest_curr_month_create_4HR_Count'] != NULL)
        {
            $model->bcs_DR_dest_curr_month_create_4HR_Count = $modelBookingCitiesStats['bcs_DR_dest_curr_month_create_4HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_dest_curr_month_create_8HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_dest_curr_month_create_8HR_Count'] != NULL)
        {
            $model->bcs_DR_dest_curr_month_create_8HR_Count = $modelBookingCitiesStats['bcs_DR_dest_curr_month_create_8HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_dest_curr_month_create_12HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_dest_curr_month_create_12HR_Count'] != NULL)
        {
            $model->bcs_DR_dest_curr_month_create_12HR_Count = $modelBookingCitiesStats['bcs_DR_dest_curr_month_create_12HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AP_dest_curr_month_create_Count'] != '' && $modelBookingCitiesStats['bcs_AP_dest_curr_month_create_Count'] != NULL)
        {
            $model->bcs_AP_dest_curr_month_create_Count = $modelBookingCitiesStats['bcs_AP_dest_curr_month_create_Count'];
        }

        if ($modelBookingCitiesStats['bcs_OW_dest_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_OW_dest_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_OW_dest_prev_3month_create_Count = $modelBookingCitiesStats['bcs_OW_dest_prev_3month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_RT_dest_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_RT_dest_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_RT_dest_prev_3month_create_Count = $modelBookingCitiesStats['bcs_RT_dest_prev_3month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AT_dest_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_AT_dest_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_AT_dest_prev_3month_create_Count = $modelBookingCitiesStats['bcs_AT_dest_prev_3month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_PT_dest_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_PT_dest_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_PT_dest_prev_3month_create_Count = $modelBookingCitiesStats['bcs_PT_dest_prev_3month_create_Count'];
        }

        if ($modelBookingCitiesStats['bcs_FL_dest_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_FL_dest_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_FL_dest_prev_3month_create_Count = $modelBookingCitiesStats['bcs_FL_dest_prev_3month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_SH_dest_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_SH_dest_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_SH_dest_prev_3month_create_Count = $modelBookingCitiesStats['bcs_SH_dest_prev_3month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_CT_dest_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_CT_dest_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_CT_dest_prev_3month_create_Count = $modelBookingCitiesStats['bcs_CT_dest_prev_3month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_dest_prev_3month_create_4HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_dest_prev_3month_create_4HR_Count'] != NULL)
        {
            $model->bcs_DR_dest_prev_3month_create_4HR_Count = $modelBookingCitiesStats['bcs_DR_dest_prev_3month_create_4HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_dest_prev_3month_create_8HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_dest_prev_3month_create_8HR_Count'] != NULL)
        {
            $model->bcs_DR_dest_prev_3month_create_8HR_Count = $modelBookingCitiesStats['bcs_DR_dest_prev_3month_create_8HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_DR_dest_prev_3month_create_12HR_Count'] != '' && $modelBookingCitiesStats['bcs_DR_dest_prev_3month_create_12HR_Count'] != NULL)
        {
            $model->bcs_DR_dest_prev_3month_create_12HR_Count = $modelBookingCitiesStats['bcs_DR_dest_prev_3month_create_12HR_Count'];
        }
        if ($modelBookingCitiesStats['bcs_AP_dest_prev_3month_create_Count'] != '' && $modelBookingCitiesStats['bcs_AP_dest_prev_3month_create_Count'] != NULL)
        {
            $model->bcs_AP_dest_prev_3month_create_Count = $modelBookingCitiesStats['bcs_AP_dest_prev_3month_create_Count'];
        }
        if ($modelBookingCitiesStats['bcs_last_bkg_src_complete_date'] != '' && $modelBookingCitiesStats['bcs_last_bkg_src_complete_date'] != NULL)
        {
            $model->bcs_last_bkg_src_complete_date = $modelBookingCitiesStats['bcs_last_bkg_src_complete_date'];
        }
        if ($modelBookingCitiesStats['bcs_last_bkg_src_create_date'] != '' && $modelBookingCitiesStats['bcs_last_bkg_src_create_date'] != NULL)
        {
            $model->bcs_last_bkg_src_create_date = $modelBookingCitiesStats['bcs_last_bkg_src_create_date'];
        }
        if ($modelBookingCitiesStats['bcs_last_bkg_dst_complete_date'] != '' && $modelBookingCitiesStats['bcs_last_bkg_dst_complete_date'] != NULL)
        {
            $model->bcs_last_bkg_dst_complete_date = $modelBookingCitiesStats['bcs_last_bkg_dst_complete_date'];
        }
        if ($modelBookingCitiesStats['bcs_last_bkg_dst_create_date'] != '' && $modelBookingCitiesStats['bcs_last_bkg_dst_create_date'] != NULL)
        {
            $model->bcs_last_bkg_dst_create_date = $modelBookingCitiesStats['bcs_last_bkg_dst_create_date'];
        }
        if ($modelBookingCitiesStats['bcs_create_date'] != '' && $modelBookingCitiesStats['bcs_create_date'] != NULL)
        {
            $model->bcs_create_date = $modelBookingCitiesStats['bcs_create_date'];
        }
        if ($modelBookingCitiesStats['bcs_modified_date'] != '' && $modelBookingCitiesStats['bcs_modified_date'] != NULL)
        {
            $model->bcs_modified_date = $modelBookingCitiesStats['bcs_modified_date'];
        }
        if ($modelBookingCitiesStats['bcs_active'] != '' && $modelBookingCitiesStats['bcs_active'] != NULL)
        {
            $model->bcs_active = $modelBookingCitiesStats['bcs_active'];
        }
        if ($model->validate() && $model->save())
        {
            $success = true;
        }
        return $success;
    }

}
