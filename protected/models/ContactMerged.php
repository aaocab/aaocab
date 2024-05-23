<?php

/**
 * This is the model class for table "contact_merged".
 *
 * The followings are the available columns in table 'contact_merged':
 * @property string $cmg_id
 * @property string $cmg_ctt_id
 * @property string $cmg_mrg_ctt_id
 * @property string $cmg_vnd_ids
 * @property string $cmg_drv_ids
 * @property string $cmg_usr_ids
 * @property string $cmg_agt_ids
 * @property integer $cmg_pan_flag
 * @property integer $cmg_licence_flag
 * @property integer $cmg_voter_flag
 * @property integer $cmg_adhaar_flag
 * @property string $cmg_profile_flag Description
 * @property integer $cmg_added_by
 * @property integer $cmg_status
 * @property integer $cmg_updated_by
 * @property string $cmg_created
 * @property string $cmg_modified
 */
class ContactMerged extends CActiveRecord
{

    const TYPE_USER	 = 1;
    const TYPE_VENDOR	 = 2;
    const TYPE_DRIVER	 = 3;
    const TYPE_AGENT	 = 4;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
	return 'contact_merged';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
	return array(
	    array('cmg_ctt_id, cmg_mrg_ctt_id', 'required'),
	    array('cmg_pan_flag, cmg_licence_flag, cmg_voter_flag, cmg_adhaar_flag, cmg_added_by, cmg_status, cmg_updated_by', 'numerical', 'integerOnly' => true),
	    array('cmg_ctt_id, cmg_mrg_ctt_id', 'length', 'max' => 20),
	    // The following rule is used by search().
	    // @todo Please remove those attributes that should not be searched.
	    array('cmg_id, cmg_ctt_id, cmg_mrg_ctt_id, cmg_pan_flag, cmg_licence_flag, cmg_voter_flag, cmg_adhaar_flag, cmg_added_by, cmg_status, cmg_updated_by, cmg_created, cmg_modified', 'safe', 'on' => 'search'),
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
	    'cmg_id'		 => 'Cmg',
	    'cmg_ctt_id'		 => 'Cmg Ctt',
	    'cmg_mrg_ctt_id'	 => 'Cmg Mrg Ctt',
	    'cmg_pan_flag'		 => 'Cmg Pan Flag',
	    'cmg_licence_flag'	 => 'Cmg Licence Flag',
	    'cmg_voter_flag'	 => 'Cmg Voter Flag',
	    'cmg_adhaar_flag'	 => 'Cmg Adhaar Flag',
	    'cmg_added_by'		 => 'Cmg Added By',
	    'cmg_updated_by'	 => 'Cmg Updated By',
	    'cmg_created'		 => 'Cmg Created',
	    'cmg_modified'		 => 'Cmg Modified',
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

	$criteria->compare('cmg_id', $this->cmg_id, true);
	$criteria->compare('cmg_ctt_id', $this->cmg_ctt_id, true);
	$criteria->compare('cmg_mrg_ctt_id', $this->cmg_mrg_ctt_id, true);
	$criteria->compare('cmg_pan_flag', $this->cmg_pan_flag);
	$criteria->compare('cmg_licence_flag', $this->cmg_licence_flag);
	$criteria->compare('cmg_voter_flag', $this->cmg_voter_flag);
	$criteria->compare('cmg_adhaar_flag', $this->cmg_adhaar_flag);
	$criteria->compare('cmg_added_by', $this->cmg_added_by);
	$criteria->compare('cmg_status', $this->cmg_status);
	$criteria->compare('cmg_updated_by', $this->cmg_updated_by);
	$criteria->compare('cmg_created', $this->cmg_created, true);
	$criteria->compare('cmg_modified', $this->cmg_modified, true);

	return new CActiveDataProvider($this, array(
	    'criteria' => $criteria,
	));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ContactMerged the static model class
     */
    public static function model($className = __CLASS__)
    {
	return parent::model($className);
    }

    /**
     * 	@return ContactMerged 
     * 	@throws Exception
     *  */
    public static function add($primaryConId, $duplicateConId)
    {
	if (empty($primaryConId) || empty($duplicateConId))
	{
	    throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
	}

	$contactMerge			 = new ContactMerged();
	$contactMerge->cmg_ctt_id	 = $primaryConId;
	$contactMerge->cmg_mrg_ctt_id	 = $duplicateConId;

	if (!$contactMerge->save())
	{
	    throw new Exception(json_encode($contactMerge->getErrors()), ReturnSet::ERROR_VALIDATION);
	}
	return $contactMerge;
    }

    /**
     * 
     * @param type $drDetails
     * @throws Exception
     */
    public static function updateFlag($drDetails)
    {
	if (empty($drDetails))
	{
	    throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
	}

	foreach ($drDetails as $detail)
	{
	    $pcttId	 = $detail['pcttId'];
	    $dcttId	 = $detail['dcttId'];
	    if ($detail["vPDoc"] > 0 && $detail["vDDoc"] > 0)
	    {
		$sql = " UPDATE contact_merged SET cmg_voter_flag = 1 WHERE cmg_ctt_id = $pcttId AND cmg_mrg_ctt_id = $dcttId";
		DBUtil::command($sql)->execute();
	    }

	    if ($detail["aPDoc"] > 0 && $detail["aDDoc"] > 0)
	    {
		$sql = " UPDATE contact_merged SET cmg_adhaar_flag = 1 WHERE cmg_ctt_id = $pcttId AND cmg_mrg_ctt_id = $dcttId";
		DBUtil::command($sql)->execute();
	    }

	    if ($detail["pPDoc"] > 0 && $detail["pDDoc"] > 0)
	    {
		$sql = " UPDATE contact_merged SET cmg_pan_flag = 1 WHERE cmg_ctt_id = $pcttId AND cmg_mrg_ctt_id = $dcttId";
		DBUtil::command($sql)->execute();
	    }

	    if ($detail["lPDoc"] > 0 && $detail["lDDoc"] > 0)
	    {
		$sql = " UPDATE contact_merged SET cmg_licence_flag = 1 WHERE cmg_ctt_id = $pcttId AND cmg_mrg_ctt_id = $dcttId";
		DBUtil::command($sql)->execute();
	    }
	}
    }

    /**
     * 
     * @param int $primaryId
     * @param int $duplicateId
     * @param bool $createIfNotExist
     * @return ContactMerged
     * @throws Exception
     */
    public static function getByIds($primaryId, $duplicateId, $createIfNotExist = true)
    {
	$params	 = ['primaryId'	 => $primaryId,
	    'duplicateId'	 => $duplicateId
	];
	$sql	 = "SELECT * FROM contact_merged WHERE cmg_ctt_id=:primaryId AND cmg_mrg_ctt_id=:duplicateId";
	$model	 = self::model()->findBySql($sql, $params);
	if (!$model && $createIfNotExist)
	{
	    $model = self::add($primaryId, $duplicateId);
	}
	return $model;
    }

    /**
     * 
     * @param int $primaryId
     * @param int $duplicateId
     * @param int $refType
     * @param int $refValue
     * @return ContactMerged
     * @throws Exception
     */
    public static function updateReferenceIds($primaryId, $duplicateId, $refType, $refValue)
    {
	$model		 = self::getByIds($primaryId, $duplicateId);
	$fieldName	 = "";
	switch ($refType)
	{
	    case self::TYPE_USER:
		$fieldName	 = "cmg_usr_ids";
		break;
	    case self::TYPE_VENDOR:
		$fieldName	 = "cmg_vnd_ids";
		break;
	    case self::TYPE_DRIVER:
		$fieldName	 = "cmg_drv_ids";
		break;
	    case self::TYPE_AGENT:
		$fieldName	 = "cmg_agt_ids";
		break;
	    default:
		throw new Exception("Invalid Reference Type", ReturnSet::ERROR_INVALID_DATA);
	}

	$prefix			 = ($model->$fieldName == "") ? "" : ",";
	$model->$fieldName	 .= $prefix . $refValue;
	if (!$model->save())
	{
	    throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
	}
	return $model;
    }

    /**
     * Fetches the duplicate merge details
     * @return \CSqlDataProvider
     */
    public static function getList()
    {
	$sql		 = "SELECT cmg_ctt_id,  GROUP_CONCAT(DISTINCT cmg_mrg_ctt_id) as dupIds,  
					GROUP_CONCAT(DISTINCT IF(ctt.ctt_id=cmg_ctt_id, CONCAT(IFNULL(CONCAT(ctt_first_name, ' ', ctt_last_name),''),' (',ctt_id,')'), null)) as primaryName,
					GROUP_CONCAT(DISTINCT IF(ctt.ctt_id=cmg_mrg_ctt_id, CONCAT(CONCAT(ctt_first_name, ' ', ctt_last_name),' (',ctt_id,')'), null) SEPARATOR ', ') as duplicateName,
					cm.cmg_pan_flag, cm.cmg_licence_flag, cm.cmg_voter_flag, cm.cmg_adhaar_flag,cm.cmg_profile_flag, cm.cmg_created
					
				FROM contact ctt
					INNER JOIN contact_merged cm ON (ctt.ctt_id=cm.cmg_mrg_ctt_id AND cm.cmg_ctt_id=ctt.ctt_ref_code) OR (ctt.ctt_id=cmg_ctt_id)
					LEFT JOIN document adoc ON ctt.ctt_aadhar_doc_id=adoc.doc_id AND cm.cmg_adhaar_flag=1 AND adoc.doc_status IN (0,1)
					LEFT JOIN document pdoc ON ctt.ctt_pan_doc_id=pdoc.doc_id AND cm.cmg_pan_flag=1 AND pdoc.doc_status IN (0,1)
					LEFT JOIN document vdoc ON ctt.ctt_voter_doc_id=vdoc.doc_id AND cm.cmg_voter_flag=1 AND vdoc.doc_status IN (0,1)
					LEFT JOIN document ldoc ON ctt.ctt_license_doc_id=ldoc.doc_id AND cm.cmg_licence_flag=1 AND ldoc.doc_status IN (0,1)
				WHERE (cm.cmg_pan_flag > 0 OR cm.cmg_licence_flag > 0 OR cm.cmg_voter_flag > 0  OR cm.cmg_adhaar_flag > 0) AND cm.cmg_status = 1
				GROUP BY cm.cmg_ctt_id";
	$count		 = DBUtil::command("select count(*)  FROM ($sql) abc", DBUtil::SDB())->queryScalar();
	$dataprovider	 = new CSqlDataProvider($sql, ['db' => DBUtil::SDB(), 'totalItemCount' => $count, 'sort' => ['attributes' => ['cmg_id,cmg_ctt_id,ctt_first_name,ctt_last_name,cmg_mrg_ctt_id, cmg_pan_flag, cmg_licence_flag, cmg_voter_flag, cmg_adhaar_flag, cmg_added_by, cmg_created, cmg_modified'], 'defaultOrder' => 'cmg_ctt_id DESC'], 'pagination' => ['pageSize' => 50]]);
	return $dataprovider;
    }

    /**
     * Deactivate contact merge data
     * @param type $cttId
     * @return type
     * @throws Exception
     */
    public static function deactivate($cttId)
    {
	if (empty($cttId))
	{
	    throw new Exception("Invalid data passed", ReturnSet::ERROR_INVALID_DATA);
	}

	$sql = "UPDATE contact_merged SET cmg_status = 0 WHERE cmg_ctt_id = $cttId";
	return DBUtil::command($sql)->execute();
    }

    public function findByPrimaryCttId($cttId)
    {
	$model = self::model()->find(array("condition" => "cmg_ctt_id =$cttId"));
	return $model;
    }

    public static function updateData($cttId)
    {
	if (empty($cttId))
	{
	    throw new Exception("Invalid data passed", ReturnSet::ERROR_INVALID_DATA);
	}

	$updateSql	 = " UPDATE contact_merged SET cmg_status = 0, cmg_modified = NOW() WHERE `cmg_ctt_id` = $cttId";
	$numrows	 = DBUtil::command($updateSql)->execute();
	return true;
    }

}
