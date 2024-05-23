<?php

/**
 * This is the model class for table "agent_rel".
 *
 * The followings are the available columns in table 'agent_rel':
 * @property integer $arl_id
 * @property integer $arl_agt_id
 * @property string $arl_rel_gozoemp_name1
 * @property string $arl_rel_gozoemp_desig1
 * @property string $arl_rel_gozoemp_reltype1
 * @property string $arl_rel_gozoemp_name2
 * @property string $arl_rel_gozoemp_desig2
 * @property string $arl_rel_gozoemp_reltype2
 * @property string $arl_rel_gozoemp_name3
 * @property string $arl_rel_gozoemp_desig3
 * @property string $arl_rel_gozoemp_reltype3
 * @property string $arl_rel_gozoemp_name4
 * @property string $arl_rel_gozoemp_desig4
 * @property string $arl_rel_gozoemp_reltype4
 * @property string $arl_voter_id_path
 * @property string $arl_driver_license_path
 * @property string $arl_operating_managers
 */
class AgentRel extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'agent_rel';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('arl_agt_id', 'required'),
			array('arl_agt_id', 'numerical', 'integerOnly' => true),
			array('arl_rel_gozoemp_name1, arl_rel_gozoemp_desig1, arl_rel_gozoemp_reltype1, arl_rel_gozoemp_name2, arl_rel_gozoemp_desig2, arl_rel_gozoemp_reltype2, arl_rel_gozoemp_name3, arl_rel_gozoemp_desig3, arl_rel_gozoemp_reltype3, arl_rel_gozoemp_name4, arl_rel_gozoemp_desig4, arl_rel_gozoemp_reltype4', 'length', 'max' => 150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('arl_id, arl_agt_id, arl_rel_gozoemp_name1, arl_rel_gozoemp_desig1, arl_rel_gozoemp_reltype1, arl_rel_gozoemp_name2, arl_rel_gozoemp_desig2, arl_rel_gozoemp_reltype2, arl_rel_gozoemp_name3, arl_rel_gozoemp_desig3, arl_rel_gozoemp_reltype3, arl_rel_gozoemp_name4, arl_rel_gozoemp_desig4, arl_rel_gozoemp_reltype4,arl_voter_id_path,arl_driver_license_path,arl_operating_managers', 'safe'),
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
			'arl_id'					 => 'Arl',
			'arl_agt_id'				 => 'Arl Agt',
			'arl_rel_gozoemp_name1'		 => 'Arl Rel Gozoemp Name1',
			'arl_rel_gozoemp_desig1'	 => 'Arl Rel Gozoemp Desig1',
			'arl_rel_gozoemp_reltype1'	 => 'Arl Rel Gozoemp Reltype1',
			'arl_rel_gozoemp_name2'		 => 'Arl Rel Gozoemp Name2',
			'arl_rel_gozoemp_desig2'	 => 'Arl Rel Gozoemp Desig2',
			'arl_rel_gozoemp_reltype2'	 => 'Arl Rel Gozoemp Reltype2',
			'arl_rel_gozoemp_name3'		 => 'Arl Rel Gozoemp Name3',
			'arl_rel_gozoemp_desig3'	 => 'Arl Rel Gozoemp Desig3',
			'arl_rel_gozoemp_reltype3'	 => 'Arl Rel Gozoemp Reltype3',
			'arl_rel_gozoemp_name4'		 => 'Arl Rel Gozoemp Name4',
			'arl_rel_gozoemp_desig4'	 => 'Arl Rel Gozoemp Desig4',
			'arl_rel_gozoemp_reltype4'	 => 'Arl Rel Gozoemp Reltype4',
			'arl_voter_id_path'			 => 'Voter Id Path',
			'arl_driver_license_path'	 => 'Driving License'
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

		$criteria->compare('arl_id', $this->arl_id);
		$criteria->compare('arl_agt_id', $this->arl_agt_id);
		$criteria->compare('arl_rel_gozoemp_name1', $this->arl_rel_gozoemp_name1, true);
		$criteria->compare('arl_rel_gozoemp_desig1', $this->arl_rel_gozoemp_desig1, true);
		$criteria->compare('arl_rel_gozoemp_reltype1', $this->arl_rel_gozoemp_reltype1, true);
		$criteria->compare('arl_rel_gozoemp_name2', $this->arl_rel_gozoemp_name2, true);
		$criteria->compare('arl_rel_gozoemp_desig2', $this->arl_rel_gozoemp_desig2, true);
		$criteria->compare('arl_rel_gozoemp_reltype2', $this->arl_rel_gozoemp_reltype2, true);
		$criteria->compare('arl_rel_gozoemp_name3', $this->arl_rel_gozoemp_name3, true);
		$criteria->compare('arl_rel_gozoemp_desig3', $this->arl_rel_gozoemp_desig3, true);
		$criteria->compare('arl_rel_gozoemp_reltype3', $this->arl_rel_gozoemp_reltype3, true);
		$criteria->compare('arl_rel_gozoemp_name4', $this->arl_rel_gozoemp_name4, true);
		$criteria->compare('arl_rel_gozoemp_desig4', $this->arl_rel_gozoemp_desig4, true);
		$criteria->compare('arl_rel_gozoemp_reltype4', $this->arl_rel_gozoemp_reltype4, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgentRel the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

}
