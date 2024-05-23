<?php

/**
 * This is the model class for table "lead_note".
 *
 * The followings are the available columns in table 'lead_note':
 * @property integer $ldn_id
 * @property integer $ldn_user_id
 * @property integer $ldn_role_id
 * @property integer $ldn_role_contact_id
 * @property string $ldn_associated_record
 * @property string $ldn_adm_user_id
 * @property string $ldn_create_time
 * @property integer $ldn_status
 */
class LeadNote extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lead_note';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('ldn_user_id, ldn_role_id, ldn_role_contact_id, ldn_associated_record, ldn_adm_user_id, ldn_create_time', 'required'),
			array('ldn_user_id, ldn_role_id, ldn_role_contact_id', 'numerical', 'integerOnly' => true),
			array('ldn_associated_record, ldn_adm_user_id', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ldn_id, ldn_user_id, ldn_role_id, ldn_role_contact_id, ldn_associated_record, ldn_adm_user_id, ldn_create_time, ldn_status', 'safe', 'on' => 'search'),
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
			'ldn_id'				 => 'Ldn',
			'ldn_user_id'			 => 'Ldn User',
			'ldn_role_id'			 => 'Ldn Role',
			'ldn_role_contact_id'	 => 'Ldn Role Contact',
			'ldn_associated_record'	 => 'Ldn Associated Record',
			'ldn_adm_user_id'		 => 'Ldn Adm User',
			'ldn_create_time'		 => 'Ldn Create Time',
			'ldn_status'			 => 'Ldn Status',
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

		$criteria->compare('ldn_id', $this->ldn_id);
		$criteria->compare('ldn_user_id', $this->ldn_user_id);
		$criteria->compare('ldn_role_id', $this->ldn_role_id);
		$criteria->compare('ldn_role_contact_id', $this->ldn_role_contact_id);
		$criteria->compare('ldn_associated_record', $this->ldn_associated_record, true);
		$criteria->compare('ldn_adm_user_id', $this->ldn_adm_user_id, true);
		$criteria->compare('ldn_create_time', $this->ldn_create_time, true);
		$criteria->compare('ldn_status', $this->ldn_status, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LeadNote the static model class
	 */

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function createLog($refid, $reftype,$desc,$eventid)
	{
		$success = false;
		$errors	 = '';
	
		$leadNote					 = new LeadNote();
		$leadNote->ldn_lead_id		 = $refid;
		$leadNote->ldn_lead_type	 = $reftype;
		$leadNote->ldn_notes		 = $desc;		
		$leadNote->ldn_user_id		 = $userInfo->userId;
		$leadNote->ldn_adm_user_id	 = UserInfo::getUserId();
		$leadNote->ldn_status        = 1;

		if ($leadNote->validate())
		{

			if ($leadNote->save())
			{
				$success = true;
			}
			else
			{
				$getErrors = json_encode($leadNote->getErrors());
			}
		}
		else
		{
			$getErrors = json_encode($leadNote->getErrors());
		}
		return $leadNote;
	}

	public function getAssignTime($refType, $bkg_id, $csr, $event)
	{
		$sql = "SELECT alg_created FROM assign_log WHERE "
				. "alg_ref_type = $refType AND alg_ref_id = $bkg_id"
				. " AND alg_user_id=$csr AND alg_event_id=$event AND alg_active=1 ORDER BY alg_id DESC LIMIT 1";

		//$recordSet = DBUtil::queryScalar($sql, DBUtil::SDB());
		$dt = DBUtil::command($sql)->queryScalar();
		return $dt;
	}

	public function  getCountByCsr($csr)
	{
	$sql = "SELECT COUNT(*) as tot FROM assign_log WHERE "
					. "  alg_user_id=$csr AND DATE(alg_created)=CURDATE() AND alg_active=1";


			$count = DBUtil::command($sql)->queryScalar();
			return $count;
	}

	public function add($param = array())
	{
		$success						 = false;
		$ldn_associated_record			 = $param['bkgId'] . '>>' . $param['note'];
		$model							 = new LeadNote();
		$model->ldn_associated_record	 = $ldn_associated_record;
		$model->ldn_notes				 = $param['note'];
		$model->ldn_adm_user_id			 = UserInfo::getUserId();
		$model->ldn_user_id				 = $param['userId'];
		$model->ldn_lead_type			 = $param['type'];
		$model->ldn_lead_id				 = $param['bkgId'];
		//$model->ldn_role_id;
		//$model->ldn_role_contact_id;
		if ($model->save())
		{
			return $model;
		}
		//return $success;
	}

	public function getNotes($ldn_user_id)
	{

		$sqlParams	 = [':usrId' => $ldn_user_id];
		$sql		 = "SELECT usr_name,usr_lname,ldn_lead_type,ldn_create_time,ldn_notes,ldn_adm_user_id,ldn_lead_type,ldn_lead_id FROM `lead_note` 
		LEFT JOIN users ON ldn_user_id=user_id
		WHERE `ldn_user_id`=:usrId";
		$result		 = DBUtil::command($sql, DBUtil::SDB())->setFetchMode(PDO::FETCH_OBJ)->queryAll(true, $sqlParams);
		return $result;
	}

}
