<?php

/**
 * This is the model class for table "ledger_data_requests".
 *
 * The followings are the available columns in table 'ledger_data_requests':
 * @property integer $ldr_id
 * @property string $ldr_form_input
 * @property string $ldr_data_filepath
 * @property string $ldr_s3_data
 * @property integer $ldr_created_by
 * @property string $ldr_created_date
 * @property string $ldr_completed_date
 * @property integer $ldr_status
 */
class LedgerDataRequests extends CActiveRecord
{

	public $fromDate, $toDate, $from_ledger_id, $to_ledger_id, $group_by_type, $groupby_period, $group_by_partner;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ledger_data_requests';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ldr_form_input, ldr_created_by', 'required'),
			array('ldr_created_by, ldr_status', 'numerical', 'integerOnly' => true),
			array('ldr_form_input, ldr_s3_data', 'length', 'max' => 2000),
			array('ldr_data_filepath', 'length', 'max' => 250),
			array('ldr_completed_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ldr_id, ldr_form_input, ldr_data_filepath, ldr_s3_data, ldr_created_by, ldr_created_date, ldr_completed_date, ldr_status', 'safe', 'on' => 'search'),
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
			'ldr_id'			 => 'Ldr',
			'ldr_form_input'	 => 'Ldr Form Input',
			'ldr_data_filepath'	 => 'Ldr Data Filepath',
			'ldr_s3_data'		 => 'Ldr S3 Data',
			'ldr_created_by'	 => 'Ldr Created By',
			'ldr_created_date'	 => 'Ldr Created Date',
			'ldr_completed_date' => 'Ldr Completed Date',
			'ldr_status'		 => 'Ldr Status',
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

		$criteria->compare('ldr_id', $this->ldr_id);
		$criteria->compare('ldr_form_input', $this->ldr_form_input, true);
		$criteria->compare('ldr_data_filepath', $this->ldr_data_filepath, true);
		$criteria->compare('ldr_s3_data', $this->ldr_s3_data, true);
		$criteria->compare('ldr_created_by', $this->ldr_created_by);
		$criteria->compare('ldr_created_date', $this->ldr_created_date, true);
		$criteria->compare('ldr_completed_date', $this->ldr_completed_date, true);
		$criteria->compare('ldr_status', $this->ldr_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LedgerDataRequests the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getPendingLedger()
	{
		$sql	 = "SELECT ldr_id FROM `ledger_data_requests` WHERE `ldr_status` = 1 ORDER BY `ldr_id` ASC limit 0,1";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB());
		return $data;
	}
	
	/**
	 * 
	 * @param integer $path
	 * @return boolean
	 * @throws Exception
	 */
	public function saveCSVPath($path)
	{
		$this->ldr_data_filepath = $path;
		$this->ldr_status		 = 3;
		return $this->save();
	}

	public function getList()
    {
        $pageSize     = 50;
        $sql          = "SELECT json_value(ldr_form_input,'$.from_date') AS ldr_from_date,
						json_value(ldr_form_input,'$.to_date') AS ldr_to_date,
						json_value(ldr_form_input,'$.from_ledger_id') AS ldr_from_ledger_id,
						json_value(ldr_form_input,'$.to_ledger_id') AS ldr_to_ledger_id,
						json_value(ldr_form_input,'$.groupby_period') AS ldr_groupby_period,
						json_value(ldr_form_input,'$.groupby_type') AS ldr_groupby_type,ldr_created_date,
						ldr_status,ldr_data_filepath,ldr_id,
						adm.adm_fname, adm.adm_lname 
						FROM  ledger_data_requests 
						INNER JOIN admins adm ON adm.adm_id = ledger_data_requests.ldr_created_by";
        $count        = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
        $dataprovider = new CSqlDataProvider($sql, [
            'totalItemCount' => $count,
            'sort'           => ['attributes'   => ['ldr_created_date'],
                'defaultOrder' => 'ldr_created_date  DESC'], 'pagination'     => ['pageSize' => $pageSize],
        ]);
        return $dataprovider;
    }

}
