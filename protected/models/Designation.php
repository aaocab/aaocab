
<?php

/**
 * This is the model class for table "designation".
 *
 * The followings are the available columns in table 'designation':
 * @property integer $des_id
 * @property double $des_org_stack
 * @property string $des_name
 * @property string $des_is_manage
 * @property integer $des_status
 * @property string $des_created
 * @property string $des_modified
 */
class Designation extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'designation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('des_name, des_created, des_modified', 'required'),
            array('des_status', 'numerical', 'integerOnly'=>true),
            array('des_org_stack', 'numerical'),
            array('des_name', 'length', 'max'=>200),
            array('des_is_manage', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('des_id, des_org_stack, des_name, des_is_manage, des_status, des_created, des_modified', 'safe', 'on'=>'search'),
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
            'des_id' => 'Des',
            'des_org_stack' => 'Des Org Stack',
            'des_name' => 'Des Name',
            'des_is_manage' => 'Des Is Manage',
            'des_status' => 'Des Status',
            'des_created' => 'Des Created',
            'des_modified' => 'Des Modified',
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

        $criteria->compare('des_id',$this->des_id);
        $criteria->compare('des_org_stack',$this->des_org_stack);
        $criteria->compare('des_name',$this->des_name,true);
        $criteria->compare('des_is_manage',$this->des_is_manage,true);
        $criteria->compare('des_status',$this->des_status);
        $criteria->compare('des_created',$this->des_created,true);
        $criteria->compare('des_modified',$this->des_modified,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Designation the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
	public static function getList()
	{
		$sql = "SELECT `des_id`,`des_name` FROM `designation` WHERE `des_status`=1";
		$rows	 = DBUtil::queryAll($sql, DBUtil::SDB());
		
		$arrResponse = [];
		foreach ($rows as $team)
		{
			$arrResponse[$team["des_id"]] = $team["des_name"];
		}
		return $arrResponse;
	}
}
