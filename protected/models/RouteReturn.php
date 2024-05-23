<?php

/**
 * This is the model class for table "route_return".
 *
 * The followings are the available columns in table 'route_return':
 * @property integer $rtn_id
 * @property integer $rtn_route_id
 * @property integer $rtn_relation_id
 * @property string $rtn_distance
 * @property string $rtn_time
 *
 * The followings are the available model relations:
 * @property Route $rtnRoute
 */
class RouteReturn extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'route_return';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rtn_route_id, rtn_relation_id, rtn_distance, rtn_time', 'required'),
			array('rtn_route_id, rtn_relation_id', 'numerical', 'integerOnly' => true),
			array('rtn_distance, rtn_time', 'length', 'max' => 200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rtn_id, rtn_route_id, rtn_relation_id, rtn_distance, rtn_time', 'safe', 'on' => 'search'),
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
			'rtnRoute' => array(self::BELONGS_TO, 'Route', 'rtn_route_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rtn_id'			 => 'Rtn',
			'rtn_route_id'		 => 'Rtn Route',
			'rtn_relation_id'	 => 'Rtn Relation',
			'rtn_distance'		 => 'Rtn Distance',
			'rtn_time'			 => 'Rtn Time',
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

		$criteria->compare('rtn_id', $this->rtn_id);
		$criteria->compare('rtn_route_id', $this->rtn_route_id);
		$criteria->compare('rtn_relation_id', $this->rtn_relation_id);
		$criteria->compare('rtn_distance', $this->rtn_distance, true);
		$criteria->compare('rtn_time', $this->rtn_time, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RouteReturn the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

}
