<?php


/**
 * This is the model class for table "account_group".
 *
 * The followings are the available columns in table 'account_group':
 * @property string $accountGroupId
 * @property string $accountGroupName
 * @property string $groupUnder
 * @property string $narration
 * @property integer $isDefault
 * @property string $nature
 * @property string $affectGrossProfit
 * @property string $extraDate
 * @property string $extra1
 * @property string $extra2
 */
class AccountGroup extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'account_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('isDefault', 'numerical', 'integerOnly' => true),
			array('accountGroupName, extra2', 'length', 'max' => 255),
			array('groupUnder', 'length', 'max' => 20),
			array('nature, affectGrossProfit', 'length', 'max' => 50),
			array('extra1', 'length', 'max' => 2000),
			array('narration, extraDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('accountGroupId, accountGroupName, groupUnder, narration, isDefault, nature, affectGrossProfit, extraDate, extra1, extra2', 'safe', 'on' => 'search'),
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
			'accountGroupId'	 => 'Account Group',
			'accountGroupName'	 => 'Account Group Name',
			'groupUnder'		 => 'Group Under',
			'narration'			 => 'Narration',
			'isDefault'			 => 'Is Default',
			'nature'			 => 'Nature',
			'affectGrossProfit'	 => 'Affect Gross Profit',
			'extraDate'			 => 'Extra Date',
			'extra1'			 => 'Extra1',
			'extra2'			 => 'Extra2',
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

		$criteria->compare('accountGroupId', $this->accountGroupId, true);
		$criteria->compare('accountGroupName', $this->accountGroupName, true);
		$criteria->compare('groupUnder', $this->groupUnder, true);
		$criteria->compare('narration', $this->narration, true);
		$criteria->compare('isDefault', $this->isDefault);
		$criteria->compare('nature', $this->nature, true);
		$criteria->compare('affectGrossProfit', $this->affectGrossProfit, true);
		$criteria->compare('extraDate', $this->extraDate, true);
		$criteria->compare('extra1', $this->extra1, true);
		$criteria->compare('extra2', $this->extra2, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccountGroup the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

}
