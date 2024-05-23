<?php

/**
 * This is the model class for table "query_log".
 *
 * The followings are the available columns in table 'query_log':
 * @property integer $qlg_id
 * @property string $qlg_query
 * @property string $qlg_desc
 * @property integer $qlg_rows_effected
 * @property integer $qlg_admin_id
 * @property string $qlg_created
 */
class QueryLog extends CActiveRecord
{
	public $qlg_created1;
	public $qlg_created2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'query_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('qlg_query, qlg_desc, qlg_rows_effected, qlg_admin_id', 'required'),
			array('qlg_rows_effected, qlg_admin_id', 'numerical', 'integerOnly'=>true),
			array('qlg_query', 'length', 'max'=>5000),
			array('qlg_desc', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('qlg_id, qlg_query, qlg_desc, qlg_rows_effected, qlg_admin_id, qlg_created', 'safe', 'on'=>'search'),
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
			'qlg_id' => 'Qlg',
			'qlg_query' => 'Qlg Query',
			'qlg_desc' => 'Qlg Desc',
			'qlg_rows_effected' => 'Qlg Rows Effected',
			'qlg_admin_id' => 'Qlg Admin',
			'qlg_created' => 'Qlg Created',
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

		$criteria->compare('qlg_id',$this->qlg_id);
		$criteria->compare('qlg_query',$this->qlg_query,true);
		$criteria->compare('qlg_desc',$this->qlg_desc,true);
		$criteria->compare('qlg_rows_effected',$this->qlg_rows_effected);
		$criteria->compare('qlg_admin_id',$this->qlg_admin_id);
		$criteria->compare('qlg_created',$this->qlg_created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QueryLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/* This function is used for listing query Log */

	public function getList($model= null)
	{
		$qlg_created1 = $model->qlg_created1;
		$qlg_created2 = $model->qlg_created2;
		$qlg_admin_id = $model->qlg_admin_id;
		$where = "";
		if(!empty($qlg_created1) && !empty($qlg_created2)){
			$where .= " AND qlg_created BETWEEN '".$qlg_created1."' AND '".$qlg_created2."'";
		}
		if(!empty($qlg_admin_id)){
			$where .= " AND qlg_admin_id = ". $qlg_admin_id;
		}
		$query			 = "SELECT * FROM `query_log`  
							join admins on query_log.qlg_admin_id=admins.adm_id
							".$where;
		$queryCount		 = "SELECT COUNT(*) FROM (" . $query . ") a";
		$count			 = DBUtil::command($queryCount)->queryScalar();
		$dataprovider	 = new CSqlDataProvider($query, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['qlg_id', 'qlg_query', 'qlg_desc', 'qlg_rows_effected',
					'qlg_admin_id', 'qlg_created', 'adm_fname', 'adm_lname'],
				'defaultOrder'	 => 'qlg_created desc'
			],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	/*
	 * This function is used to get List of all the admin which are present in Querylog 
	 * @return array
	 */

	public function getAllQueryLogAdmin()
	{
		//$adminModels = Admins::model()->getAll();
		$qry		 = 'SELECT admins.adm_id as adm_id,CONCAT(admins.adm_fname," ",admins.adm_lname) as adm_name FROM `query_log` INNER JOIN admins ON admins.adm_id = qlg_admin_id';
		$logList	 = DBUtil::query($qry);
		$arrSkill	 = array();
		if (!empty($logList))
		{
			foreach ($logList as $sklModel)
			{				
				$arrSkill[$sklModel['adm_id']] = $sklModel['adm_name'];
			}
		}
		return $arrSkill;
	}

}
