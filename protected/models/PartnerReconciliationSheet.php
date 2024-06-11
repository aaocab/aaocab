<?php

/**
 * This is the model class for table "partner_reconciliation_sheet".
 *
 * The followings are the available columns in table 'partner_reconciliation_sheet':
 * @property integer $prs_id
 * @property string $prs_title
 * @property string $prs_pickup_from_date
 * @property string $prs_pickup_to_date
 * @property integer $prs_sheet_type
 * @property string $prs_filename
 * @property string $prs_row_count
 * @property string $prs_success_count
 * @property string $prs_failed_count
 * @property integer $prs_status
 * @property integer $prs_created_by
 * @property string $prs_create_date
 */
class PartnerReconciliationSheet extends CActiveRecord
{

	public $arrSheetType = ["1" => "Payout Sheet", "2" => "Penalty Sheet", "3" => "Compensation Sheet"];
	public $arrStatus	 = ["1" => "Pending", "2" => "Processing", "3" => "Completed", "4" => "Failed"];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'partner_reconciliation_sheet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prs_filename, prs_created_by, prs_create_date', 'required'),
			array('prs_sheet_type, prs_row_count, prs_status, prs_created_by', 'numerical', 'integerOnly' => true),
			array('prs_title, prs_filename', 'length', 'max' => 100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prs_id, prs_title, prs_pickup_from_date, prs_pickup_to_date, prs_sheet_type, prs_filename, prs_row_count, prs_success_count, prs_failed_count, prs_status, prs_created_by, prs_create_date', 'safe', 'on' => 'search'),
			array('prs_id, prs_title, prs_pickup_from_date, prs_pickup_to_date, prs_sheet_type, prs_filename, prs_row_count, prs_success_count, prs_failed_count, prs_status, prs_created_by, prs_create_date', 'safe'),
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
			'prs_id'				 => 'Prs',
			'prs_title'				 => 'Prs Title',
			'prs_pickup_from_date'	 => 'prs_pickup_from_date',
			'prs_pickup_to_date'	 => 'prs_pickup_to_date',
			'prs_sheet_type'		 => 'Prs Sheet Type',
			'prs_filename'			 => 'Prs Filename',
			'prs_row_count'			 => 'Prs Row Count',
			'prs_success_count'		 => 'Prs Success Count',
			'prs_failed_count'		 => 'Prs Failed Count',
			'prs_status'			 => 'Prs Status',
			'prs_created_by'		 => 'Prs Created By',
			'prs_create_date'		 => 'Prs Create Date',
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

		$criteria->compare('prs_id', $this->prs_id);
		$criteria->compare('prs_title', $this->prs_title, true);
		$criteria->compare('prs_pickup_from_date', $this->prs_pickup_from_date, true);
		$criteria->compare('prs_pickup_to_date', $this->prs_pickup_to_date, true);
		$criteria->compare('prs_sheet_type', $this->prs_sheet_type);
		$criteria->compare('prs_filename', $this->prs_filename, true);
		$criteria->compare('prs_row_count', $this->prs_row_count);
		$criteria->compare('prs_success_count', $this->prs_success_count);
		$criteria->compare('prs_failed_count', $this->prs_failed_count);
		$criteria->compare('prs_status', $this->prs_status);
		$criteria->compare('prs_created_by', $this->prs_created_by);
		$criteria->compare('prs_create_date', $this->prs_create_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartnerReconciliationSheet the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function addSheet($arrSheet, $fileName)
	{
		$currDate = date("Y-m-d H:i:s");

		$modelSheet							 = new PartnerReconciliationSheet();
		$modelSheet->prs_title				 = trim($arrSheet['prs_title']);
		$modelSheet->prs_sheet_type			 = trim($arrSheet['prs_sheet_type']);
		$modelSheet->prs_pickup_from_date	 = trim($arrSheet['prs_pickup_from_date']);
		$modelSheet->prs_pickup_to_date		 = trim($arrSheet['prs_pickup_to_date']);
		$modelSheet->prs_filename			 = $fileName;
		$modelSheet->prs_status				 = 1;
		$modelSheet->prs_created_by			 = UserInfo::getUserId();
		$modelSheet->prs_create_date		 = $currDate;
		if (!$modelSheet->save())
		{
			throw new Exception(json_encode($modelSheet->getErrors()));
		}

		return $modelSheet;
	}

	public function getList()
	{
		$sql			 = "SELECT * FROM partner_reconciliation_sheet";
		$sqlCount		 = "SELECT COUNT(1) cnt FROM partner_reconciliation_sheet";
		$count			 = DBUtil::command($sqlCount, DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'pagination'	 => false,
			'sort'			 => ['defaultOrder' => 'prs_id DESC'],
			'pagination'	 => ['pageSize' => 50],
		]);

		return $dataprovider;
	}

}
