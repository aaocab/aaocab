<?php

/**
 * This is the model class for table "report".
 *
 * The followings are the available columns in table 'report':
 * @property integer $rpt_id
 * @property string $rpt_name
 * @property string $rpt_categories
 * @property string $rpt_menu_name
 * @property string $rpt_link
 * @property string $rpt_short_url
 * @property string $rpt_params
 * @property string $rpt_description
 * @property string $rpt_tags
 * @property string $rpt_keywords
 * @property string $rpt_roles
 * @property string $rpt_export_roles
 * @property string $rpt_create_date
 * @property integer $rpt_status
 */
class Report extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'report';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rpt_status', 'numerical', 'integerOnly' => true),
			array('rpt_name, rpt_categories, rpt_menu_name, rpt_link, rpt_short_url, rpt_params, rpt_tags, rpt_keywords, rpt_roles,rpt_export_roles', 'length', 'max' => 255),
			array('rpt_description, rpt_create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rpt_id, rpt_name, rpt_categories, rpt_menu_name, rpt_link, rpt_short_url, rpt_params, rpt_description, rpt_tags, rpt_keywords, rpt_roles, rpt_create_date, rpt_status,rpt_export_roles', 'safe', 'on' => 'search'),
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
			'rpt_id'			 => 'Report Id',
			'rpt_name'			 => 'Report Name',
			'rpt_categories'	 => 'Report Categories',
			'rpt_menu_name'		 => 'Report Menu Name',
			'rpt_link'			 => 'Report Link',
			'rpt_short_url'		 => 'Report Short Url',
			'rpt_params'		 => 'Report Params',
			'rpt_description'	 => 'Report Description',
			'rpt_tags'			 => 'Report Tags',
			'rpt_keywords'		 => 'Report Keywords',
			'rpt_roles'			 => 'Report Roles',
			'rpt_export_roles'	 => 'Report Export Roles',
			'rpt_create_date'	 => 'Report Create Date',
			'rpt_status'		 => 'Report Status',
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

		$criteria->compare('rpt_id', $this->rpt_id);
		$criteria->compare('rpt_name', $this->rpt_name, true);
		$criteria->compare('rpt_categories', $this->rpt_categories, true);
		$criteria->compare('rpt_menu_name', $this->rpt_menu_name, true);
		$criteria->compare('rpt_link', $this->rpt_link, true);
		$criteria->compare('rpt_short_url', $this->rpt_short_url, true);
		$criteria->compare('rpt_params', $this->rpt_params, true);
		$criteria->compare('rpt_description', $this->rpt_description, true);
		$criteria->compare('rpt_tags', $this->rpt_tags, true);
		$criteria->compare('rpt_keywords', $this->rpt_keywords, true);
		$criteria->compare('rpt_roles', $this->rpt_roles, true);
		$criteria->compare('rpt_export_roles', $this->rpt_export_roles, true);
		$criteria->compare('rpt_create_date', $this->rpt_create_date, true);
		$criteria->compare('rpt_status', $this->rpt_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Report the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function searchFullText($query = '')
	{
		$query	 = ($query == null || $query == "") ? "" : $query;
		($query == null || $query == "") ? DBUtil::getLikeStatement($query, $bindString, $params, '"', '"') : DBUtil::getLikeStatement($query, $bindString, $params, '"', '"');
		DBUtil::getLikeStatement($query, $bindString1, $params1);
		$qry	 = "";
		if ($query != '')
		{
			$qry	 .= " OR  rpt_name LIKE $bindString1 OR  rpt_menu_name LIKE $bindString1 ";
			$params	 = array_merge($params, $params1);
		}
		$sql = "SELECT 
				rpt_id,
				rpt_name,
				rpt_link,
				MATCH(rpt_name,rpt_description,rpt_tags,rpt_keywords,rpt_menu_name) AGAINST ($bindString IN NATURAL LANGUAGE MODE) AS score,
				IF(rpt_name LIKE $bindString,1,0) AS startRank
				FROM report 
				WHERE 1
				AND 
				( 
					MATCH(rpt_name,rpt_description,rpt_tags,rpt_keywords,rpt_menu_name) AGAINST ($bindString IN NATURAL LANGUAGE MODE)
					$qry
				)
				AND rpt_status=1
				ORDER BY score DESC,startRank DESC, rpt_name ASC";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	/**
	 *
	 * @param integer $query
	 * @param string $city
	 * @return type
	 */
	public static function getJSON($query = "", $status = 0)
	{
		$rows	 = self::searchFullText($query);
		$data	 = array();
		foreach ($rows as $row)
		{
			$data[] = array("label" => $row['rpt_name'], "value" => $row['rpt_link']);
		}
		if ($status == 1)
		{
			$data = CJSON::encode($arrCities);
		}
		return $data;
	}

	/**
	 * This function will get all menu link  for report section
	 * @return query object
	 */
	public static function getAllMenu($reportCat = 0)
	{
		$where = "";
		if ($reportCat > 0)
		{
			$where .= " AND FIND_IN_SET(report_category.rpc_id, $reportCat) ";
		}
		$sql = "SELECT 
			report.rpt_id,
			report.rpt_link,
			report.rpt_description,
			report.rpt_categories,
			report_category.rpc_name,
			report.rpt_name,
			report_category.rpc_id,
			report_category.rpc_cat_icons
			FROM report
			INNER JOIN report_category ON FIND_IN_SET(report_category.rpc_id, report.rpt_categories)
			WHERE 1
			AND report.rpt_status=1
			AND report_category.rpc_status=1
			$where
			GROUP BY report_category.rpc_name,rpt_id ORDER BY rpc_sort ASC,report.rpt_name ASC ";
		return DBUtil::queryAll($sql, DBUtil::SDB(), [], true, 60 * 60 * 24, CacheDependency::Type_Report_DashBoard);
	}

	/**
	 * This function will get all roles  for report section
	 * @return query row
	 */
	public static function getRoleAccess($reportId)
	{
		$sql = "SELECT report.rpt_roles,report.rpt_export_roles	FROM report	WHERE 1	AND report.rpt_status=1	AND report.rpt_id=:reportId	";
		return DBUtil::queryRow($sql, DBUtil::SDB(), array('reportId' => $reportId), true, 60 * 60 * 24, CacheDependency::Type_Report_DashBoard);
	}

}
