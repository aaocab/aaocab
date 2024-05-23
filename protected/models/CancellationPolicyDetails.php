<?php

/**
 * This is the model class for table "cancellation_policy".
 *
 * The followings are the available columns in table 'cancellation_policy':
 * @property integer $cnp_id
 * @property string $cnp_code
 * @property string $cnp_label
 * @property string $cnp_desc
 * @property string $cnp_rule_data
 * @property integer $cnp_active
 * @property string $created_at
 * @property string $modified_at
 */
class CancellationPolicyDetails extends CActiveRecord
{

	const NON_CANCELLABLE = 20;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cancellation_policy_details';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cnp_code, cnp_label, cnp_desc, cnp_rule_data, cnp_active, created_at', 'required'),
			array('cnp_active', 'numerical', 'integerOnly'=>true),
			array('cnp_code', 'length', 'max'=>50),
			array('cnp_label', 'length', 'max'=>100),
			array('modified_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cnp_id, cnp_code, cnp_label, cnp_desc, cnp_rule_data, cnp_active, created_at, modified_at', 'safe', 'on'=>'search'),
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
			'cnp_id' => 'Cnp',
			'cnp_code' => 'Cnp Code',
			'cnp_label' => 'Cnp Label',
			'cnp_desc' => 'Cnp Desc',
			'cnp_rule_data' => 'Cnp Rule Data',
			'cnp_active' => 'Cnp Active',
			'created_at' => 'Created At',
			'modified_at' => 'Modified At',
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

		$criteria->compare('cnp_id',$this->cnp_id);
		$criteria->compare('cnp_code',$this->cnp_code,true);
		$criteria->compare('cnp_label',$this->cnp_label,true);
		$criteria->compare('cnp_desc',$this->cnp_desc,true);
		$criteria->compare('cnp_rule_data',$this->cnp_rule_data,true);
		$criteria->compare('cnp_active',$this->cnp_active);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CancellationPolicy the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used to return all the rules or logic applicable with the respective cancellation policy
	 * @return array of rules
	 */
	public static function getRulesData()
	{
		$rules_data = [];
		$sql = "SELECT cnp_id ,cnp_rule_data FROM `cancellation_policy_details` WHERE  cnp_active = 1";
		$results = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $result)
		{
			$rules_data[$result['cnp_id']] = json_decode($result['cnp_rule_data'],true);
		}
		return $rules_data;
	}

	/**
	 * this function is used to return the cancellation policy code
	 * @param type $id
	 * @return type
	 */
	public static function getCodeById($id)
	{
		$sql = "SELECT cnp_code FROM `cancellation_policy_details` WHERE cnp_id=:id AND cnp_active = 1";
		$code = DBUtil::queryScalar($sql, DBUtil::SDB(),['id'=>$id]);
		return $code;
	}
}
