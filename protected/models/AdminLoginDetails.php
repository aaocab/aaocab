<?php

/**
 * This is the model class for table "admin_login_details".
 *
 * The followings are the available columns in table 'admin_login_details':
 * @property integer $ald_id
 * @property integer $ald_admin_id
 * @property integer $ald_status
 * @property string $ald_time
 */
class AdminLoginDetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'admin_login_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ald_admin_id, ald_status, ald_time', 'required'),
			array('ald_admin_id, ald_status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ald_id, ald_admin_id, ald_status, ald_time', 'safe', 'on'=>'search'),
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
			'ald_id' => 'Ald',
			'ald_admin_id' => 'Ald Admin',
			'ald_status' => 'Ald Status',
			'ald_time' => 'Ald Time',
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

		$criteria=new CDbCriteria;

		$criteria->compare('ald_id',$this->ald_id);
		$criteria->compare('ald_admin_id',$this->ald_admin_id);
		$criteria->compare('ald_status',$this->ald_status);
		$criteria->compare('ald_time',$this->ald_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AdminLoginDetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
	    return parent::model($className);
	}

        public function addAdminDetailsStatus($data){
            
            $model = new AdminLoginDetails();
          
            $model->ald_admin_id = $data['ald_admin_id'];
            $model->ald_status   = $data['ald_status'];     
            $model->ald_time     = new CDbExpression('NOW()');
            
            if($model->save()){
                return true;
            }
            return false;
        }
}
