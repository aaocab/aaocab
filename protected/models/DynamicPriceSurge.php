<?php

/**
 * This is the model class for table "dynamic_price_surge".
 *
 * The followings are the available columns in table 'dynamic_price_surge':
 * @property integer $dps_id
 * @property string $dps_name
 * @property integer $additional_surge
 * @property integer $base_capacity
 * @property string $count_booking
 * @property string $count_quotation
 * @property string $Date
 * @property integer $forecast_act
 * @property integer $M_000
 * @property string $M_010
 * @property string $M_020
 * @property string $M_030
 * @property string $M_040
 * @property string $M_050
 * @property string $M_060
 * @property string $M_070
 * @property string $M_080
 * @property string $M_090
 * @property string $M_100
 * @property string $M_120
 * @property string $M_140
 * @property string $M_170
 * @property string $M_200
 * @property string $M_250
 * @property string $M_300
 * @property integer $total_DP
 * @property integer $total_SP
 * @property string $Weekday
 * @property string $Yield
 */
class DynamicPriceSurge extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dynamic_price_surge';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('additional_surge, base_capacity, forecast_act, M_000, total_DP, total_SP', 'numerical', 'integerOnly' => true),
			array('dps_name, count_booking, count_quotation, Yield', 'length', 'max' => 256),
			array('M_010, M_020, M_030, M_040, M_050, M_060, M_070, M_080, M_090, M_100, M_120, M_140, M_170, M_200, M_250, M_300', 'length', 'max' => 2),
			array('Weekday', 'length', 'max' => 3),
			array('Date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dps_id, dps_name, additional_surge, base_capacity, count_booking, count_quotation, Date, forecast_act, M_000, M_010, M_020, M_030, M_040, M_050, M_060, M_070, M_080, M_090, M_100, M_120, M_140, M_170, M_200, M_250, M_300, total_DP, total_SP, Weekday, Yield', 'safe', 'on' => 'search'),
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
			'dps_id'			 => 'Dps',
			'dps_name'			 => 'Dps Name',
			'additional_surge'	 => 'Additional Surge',
			'base_capacity'		 => 'Base Capacity',
			'count_booking'		 => 'Count Booking',
			'count_quotation'	 => 'Count Quotation',
			'Date'				 => 'Date',
			'forecast_act'		 => 'Forecast Act',
			'M_000'				 => 'M 000',
			'M_010'				 => 'M 010',
			'M_020'				 => 'M 020',
			'M_030'				 => 'M 030',
			'M_040'				 => 'M 040',
			'M_050'				 => 'M 050',
			'M_060'				 => 'M 060',
			'M_070'				 => 'M 070',
			'M_080'				 => 'M 080',
			'M_090'				 => 'M 090',
			'M_100'				 => 'M 100',
			'M_120'				 => 'M 120',
			'M_140'				 => 'M 140',
			'M_170'				 => 'M 170',
			'M_200'				 => 'M 200',
			'M_250'				 => 'M 250',
			'M_300'				 => 'M 300',
			'total_DP'			 => 'Total Dp',
			'total_SP'			 => 'Total Sp',
			'Weekday'			 => 'Weekday',
			'Yield'				 => 'Yield',
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

		$criteria->compare('dps_id', $this->dps_id);
		$criteria->compare('dps_name', $this->dps_name, true);
		$criteria->compare('additional_surge', $this->additional_surge);
		$criteria->compare('base_capacity', $this->base_capacity);
		$criteria->compare('count_booking', $this->count_booking, true);
		$criteria->compare('count_quotation', $this->count_quotation, true);
		$criteria->compare('Date', $this->Date, true);
		$criteria->compare('forecast_act', $this->forecast_act);
		$criteria->compare('M_000', $this->M_000);
		$criteria->compare('M_010', $this->M_010, true);
		$criteria->compare('M_020', $this->M_020, true);
		$criteria->compare('M_030', $this->M_030, true);
		$criteria->compare('M_040', $this->M_040, true);
		$criteria->compare('M_050', $this->M_050, true);
		$criteria->compare('M_060', $this->M_060, true);
		$criteria->compare('M_070', $this->M_070, true);
		$criteria->compare('M_080', $this->M_080, true);
		$criteria->compare('M_090', $this->M_090, true);
		$criteria->compare('M_100', $this->M_100, true);
		$criteria->compare('M_120', $this->M_120, true);
		$criteria->compare('M_140', $this->M_140, true);
		$criteria->compare('M_170', $this->M_170, true);
		$criteria->compare('M_200', $this->M_200, true);
		$criteria->compare('M_250', $this->M_250, true);
		$criteria->compare('M_300', $this->M_300, true);
		$criteria->compare('total_DP', $this->total_DP);
		$criteria->compare('total_SP', $this->total_SP);
		$criteria->compare('Weekday', $this->Weekday, true);
		$criteria->compare('Yield', $this->Yield, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DynamicPriceSurge the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getlist($tbName)
	{
		$sql = "SELECT * FROM dynamic_price_surge WHERE dps_name = '$tbName'";
		if ($count === null)
		{
			$count = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		}
		$dataprovider = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 =>
			['attributes'	 => ['additional_surge', 'base_capacity', 'count_booking', 'count_quotation', 'Date', 'forecast_act'],
				'defaultOrder'	 => 'Date DESC'],
			'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

	public function getRouteList()
	{
		$sql	 = "SELECT DISTINCT(dps_name),dps_id FROM dynamic_price_surge";
		$rows	 = DBUtil::queryAll($sql);
		return $rows;
	}

	public function getRouteList1($all = null)
	{
		$routeModels = $this->getRouteList();
		$arrSkill	 = array();

		foreach ($routeModels as $sklModel)
		{
			$arrSkill[$sklModel['dps_id']] = $sklModel['dps_name'];
		}
		return $arrSkill;
	}

	public function getJSON($all = '')
	{
		$arrRoute	 = $this->getRouteList1();
		$arrJSON	 = [];
		if ($all != '')
		{
			$arrJSON[] = array_merge(array("id" => '0', "text" => "All"), $arrJSON);
		}
		foreach ($arrRoute as $key => $val)
		{
			if ($val != '')
			{
				$arrJSON[] = array("id" => $key, "text" => $val);
			}
		}
		$data = CJSON::encode($arrJSON);
		//$dataset = $data;
		return $data;
	}

	public function getAllTableList()
	{
		$sql		 = "SELECT DISTINCT(dps_name) FROM dynamic_price_surge";
		$recordset	 = DBUtil::queryAll($sql);
		return $recordset;
	}

	public function updateDDBPStatus()
	{
		$sql		 = "UPDATE route SET rut_override_dr = IF(rut_override_dr=1,2,1) WHERE rut_override_dr IN (1,2)";
		$recordset	 = DBUtil::command($sql)->execute();
		return $recordset;
	}

	public function importCsvIntoMysql()
	{

		$dropQry = "DROP table if exists dynamic_price_surge_test";
		DBUtil::command($dropQry)->execute();
		$create	 = "CREATE TABLE IF NOT EXISTS `dynamic_price_surge_test`(`dps_id` int(11) NOT NULL AUTO_INCREMENT,`dps_name` varchar(256) DEFAULT NULL,`dps_name_ids` varchar(255) DEFAULT NULL,
				  `dps_src_name` varchar(255) DEFAULT NULL,`dps_source_id` int(11) DEFAULT NULL,`dps_dst_name` varchar(255) DEFAULT NULL,`dps_dst_id` int(11) DEFAULT NULL,
				  `additional_surge` int(2) DEFAULT NULL,`base_capacity` int(2) DEFAULT NULL,`count_booking` varchar(256) DEFAULT '0000-00-00 00:00:00',
				  `count_quotation` varchar(256) DEFAULT '0000-00-00 00:00:00',`Date` timestamp NULL DEFAULT '0000-00-00 00:00:00',`forecast_act` int(1) NOT NULL,`M_000` int(1) NOT NULL,
				  `M_010` decimal(2,1) NOT NULL,`M_020` decimal(2,1) NOT NULL,`M_030` decimal(2,1) NOT NULL,`M_040` decimal(2,1) NOT NULL,`M_050` decimal(2,1) NOT NULL,
				  `M_060` decimal(2,1) NOT NULL,`M_070` decimal(2,1) NOT NULL,`M_080` decimal(2,1) NOT NULL,`M_090` decimal(2,1) NOT NULL,`M_100` decimal(2,1) NOT NULL,
				  `M_120` decimal(2,1) NOT NULL,`M_140` decimal(2,1) NOT NULL,`M_170` decimal(2,1) NOT NULL,`M_200` decimal(2,1) NOT NULL,`M_250` decimal(2,1) NOT NULL,
				  `M_300` decimal(2,1) NOT NULL,`manuual_count_booking` varchar(256) DEFAULT '[0,0,0,0,0,0,0,0,0,0]',`manuual_count_quotation` varchar(256) DEFAULT '[0,0,0,0,0,0,0,0,0,0]',
				  `total_DP` int(1) DEFAULT NULL,`total_SP` int(1) DEFAULT NULL,`Weekday` varchar(3) DEFAULT NULL,`Yield` varchar(256) DEFAULT NULL,PRIMARY KEY (`dps_id`)
				  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;";
		DBUtil::command($create)->execute();
		$dirPath = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'Exported';
		$ext	 = 'ddbp_route_data.csv';
		foreach (glob($dirPath . '/*' . $ext) as $filenamelist)
		{
			$filename	 = $dirPath . DIRECTORY_SEPARATOR . basename($filenamelist);
			$file		 = fopen($filename, "r");
			$row		 = 0;
			$firstRow	 = [];
			while (($getData	 = fgetcsv($file, 10000, ",")) !== FALSE)
			{
				$data = [];
				$row++;
				if ($row == 1)
				{
					$firstRow = $getData;
					continue;
				}
				else
				{
					foreach ($getData as $key => $value)
					{
						$data[$firstRow[$key]] = $value;
					}
					$preFix	 = "dynprice_";
					$dpsName = trim($preFix . ucfirst($data['Source']) . "___" . ucfirst($data['Destination']));


					$sourceId	 = Cities::model()->getIdByCity($data['Source']);
					$destId		 = Cities::model()->getIdByCity($data['Destination']);
					$dpsnameId	 = trim($preFix . $sourceId . "___" . $destId);

					if ($sourceId == '')
					{
						$sourceId = Zones::model()->getIdByName($data['Source']);
					}

					if ($destId == '')
					{
						$destId		 = Zones::model()->getIdByName($data['Destination']);
						$dpsnameId	 = trim($preFix . 'Z-' . $sourceId . "___" . 'Z-' . $destId);
						if ($destId == '')
						{
							$destId		 = States::model()->getIdByName($data['Destination']);
							$dpsnameId	 = trim($preFix . 'Z-' . $sourceId . "___" . 'S-' . $destId);
						}
					}

					$qry	 = "INSERT INTO dynamic_price_surge_test(dps_name,dps_name_ids,additional_surge,dps_src_name,dps_source_id,dps_dst_name,dps_dst_id,base_capacity,count_booking,count_quotation,Date,forecast_act,M_000,M_010,M_020,M_030,M_040,M_050,M_060,M_070,M_080,M_090,M_100,M_120,M_140,M_170,M_200,M_250,M_300,manuual_count_booking,manuual_count_quotation,Weekday,total_DP,total_SP,Yield) VALUES ('" . $dpsName . "','" . $dpsnameId . "','" . $data['additional_surge'] . "','" . $data['Source'] . "','" . $sourceId . "','" . $data['Destination'] . "','" . $destId . "','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','" . date('Y-m-d', strtotime($data['Date'])) . "','" . $data['forecast_act'] . "','" . $data['M_000'] . "','" . $data['M_010'] . "','" . $data['M_020'] . "','" . $data['M_030'] . "','" . $data['M_040'] . "','" . $data['M_050'] . "','" . $data['M_060'] . "','" . $data['M_070'] . "','" . $data['M_080'] . "','" . $data['M_090'] . "','" . $data['M_100'] . "','" . $data['M_120'] . "','" . $data['M_140'] . "','" . $data['M_170'] . "','" . $data['M_200'] . "','" . $data['M_250'] . "','" . $data['M_300'] . "','" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "','" . $data['Weekday'] . "','" . $data['total_DP'] . "','" . $data['total_SP'] . "','" . $data['Yield'] . "')";
					Logger::create("query data ====>" . $qry, CLogger::LEVEL_PROFILE);
					$result	 = DBUtil::command($qry)->execute();
				}
			}
		}
		return $result;
	}

	public function mergeDynamicPriceSurgeData()
	{
		$sql		 = "SELECT dps_name,dps_name_ids,dps_src_name,dps_source_id,dps_dst_name,dps_dst_id,additional_surge,base_capacity,count_booking,count_quotation,Date,forecast_act,M_000,M_010,M_020,M_030,M_040,M_050,M_060,M_070,M_080,M_090,M_100,M_120,M_140,M_170,M_200,M_250,M_300,manuual_count_booking,manuual_count_quotation,total_DP,total_SP,Weekday,Yield FROM dynamic_price_surge_test ";
		//Logger::create("SQL Data ====>" . $sql, CLogger::LEVEL_PROFILE);
		$recordSet	 = DBUtil::queryAll($sql);
		foreach ($recordSet as $data)
		{
			$updateQry	 = "UPDATE dynamic_price_surge SET dps_name='" . $data['dps_name'] . "',dps_name_ids='" . $data['dps_name_ids'] . "',dps_src_name='" . $data['dps_src_name'] . "',dps_source_id='" . $data['dps_source_id'] . "',dps_dst_name='" . $data['dps_dst_name'] . "',dps_dst_id='" . $data['dps_dst_id'] . "',additional_surge='" . $data['additional_surge'] . "',base_capacity='" . $data['base_capacity'] . "',Date='" . $data['Date'] . "',forecast_act='" . $data['forecast_act'] . "',M_000='" . $data['M_000'] . "',M_010='" . $data['M_010'] . "',M_020='" . $data['M_020'] . "',M_030='" . $data['M_030'] . "',M_040='" . $data['M_040'] . "',M_050='" . $data['M_050'] . "',M_060='" . $data['M_060'] . "',M_070='" . $data['M_070'] . "',M_080='" . $data['M_080'] . "',M_090='" . $data['M_090'] . "',M_100='" . $data['M_100'] . "',M_120='" . $data['M_120'] . "',M_140='" . $data['M_140'] . "',M_170='" . $data['M_170'] . "',M_200='" . $data['M_200'] . "',M_250='" . $data['M_250'] . "',M_300='" . $data['M_300'] . "',total_DP='" . $data['total_DP'] . "',total_SP='" . $data['total_SP'] . "',Weekday='" . $data['Weekday'] . "',Yield='" . $data['Yield'] . "' WHERE Date='" . date('Y-m-d', strtotime($data['Date'])) . "' AND dps_name_ids='" . $data['dps_name_ids'] . "'";
			$result		 = DBUtil::command($updateQry)->execute();
		}
		$sqlData	 = "SELECT DISTINCT(`dps_name_ids`) FROM `dynamic_price_surge`";
		$routeResult = DBUtil::command($sqlData)->queryAll();
		foreach ($routeResult as $routeData)
		{
			$getData		 = "SELECT MAX(DATE(Date)) as dt  FROM `dynamic_price_surge` WHERE `dps_name_ids` = '" . $routeData['dps_name_ids'] . "'";
			$routeMaxDate	 = DBUtil::command($getData)->queryScalar();

			$getRouteList	 = "SELECT * FROM dynamic_price_surge_test WHERE DATE(Date) > '" . $routeMaxDate['dt'] . "' AND dps_name_ids = '" . $routeData['dps_name_ids'] . "' ";
			$list			 = DBUtil::command($getRouteList)->queryAll();

			foreach ($list as $data)
			{
				$date				 = date("Y-m-d", strtotime($data['Date']));
				$getDuplicateEntry	 = "SELECT * FROM dynamic_price_surge WHERE DATE(Date) > '$date' AND dps_name_ids = '" . $routeData['dps_name_ids'] . "'";
				Logger::create("Request date ====>" . $date, CLogger::LEVEL_PROFILE);
				$duplicateDatalist	 = DBUtil::command($getDuplicateEntry)->queryRow();
				if (count($duplicateDatalist) == 0)
				{

					$insertQry	 = "INSERT INTO dynamic_price_surge(dps_name,dps_name_ids,dps_src_name,dps_source_id,dps_dst_name,dps_dst_id,additional_surge,base_capacity,count_booking,count_quotation,Date,forecast_act,M_000,M_010,M_020,M_030,M_040,M_050,M_060,M_070,M_080,M_090,M_100,M_120,M_140,M_170,M_200,M_250,M_300,manuual_count_booking,manuual_count_quotation,total_DP,total_SP,Weekday,Yield) VALUES ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','" . $data['additional_surge'] . "','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','" . date('Y-m-d', strtotime($data['Date'])) . "','" . $data['forecast_act'] . "','" . $data['M_000'] . "','" . $data['M_010'] . "','" . $data['M_020'] . "','" . $data['M_030'] . "','" . $data['M_040'] . "','" . $data['M_050'] . "','" . $data['M_060'] . "','" . $data['M_070'] . "','" . $data['M_080'] . "','" . $data['M_090'] . "','" . $data['M_100'] . "','" . $data['M_120'] . "','" . $data['M_140'] . "','" . $data['M_170'] . "','" . $data['M_200'] . "','" . $data['M_250'] . "','" . $data['M_300'] . "','" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "','" . $data['total_DP'] . "','" . $data['total_SP'] . "','" . $data['Weekday'] . "','" . $data['Yield'] . "')";
					Logger::create("INSERT Data ====>" . $insertQry, CLogger::LEVEL_PROFILE);
					$result		 = DBUtil::command($insertQry)->execute();
				}
			}
		}
		$sqlDelete = "DELETE t1 FROM dynamic_price_surge t1 INNER JOIN dynamic_price_surge t2 ON t1.dps_id=t2.dps_id WHERE t1.dps_id < t2.dps_id AND t1.dps_name = t2.dps_name AND t1.Date = t2.Date";
		DBUtil::command($sqlDelete)->execute();
	}

	public function mergeDynamicPriceSurgeNewData()
	{
		$sql		 = "SELECT dps_name,dps_name_ids,dps_src_name,dps_source_id,dps_dst_name,dps_dst_id,additional_surge,base_capacity,count_booking,count_quotation,Date,forecast_act,M_000,M_010,M_020,M_030,M_040,M_050,M_060,M_070,M_080,M_090,M_100,M_120,M_140,M_170,M_200,M_250,M_300,manuual_count_booking,manuual_count_quotation,total_DP,total_SP,Weekday,Yield FROM dynamic_price_surge_test ";
		$recordSet	 = DBUtil::queryAll($sql);
		foreach ($recordSet as $data)
		{
			$insertQry	 = "INSERT INTO dynamic_price_surge(dps_name,dps_name_ids,dps_src_name,dps_source_id,dps_dst_name,dps_dst_id,additional_surge,base_capacity,count_booking,count_quotation,Date,forecast_act,M_000,M_010,M_020,M_030,M_040,M_050,M_060,M_070,M_080,M_090,M_100,M_120,M_140,M_170,M_200,M_250,M_300,manuual_count_booking,manuual_count_quotation,total_DP,total_SP,Weekday,Yield) VALUES ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','" . $data['additional_surge'] . "','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','" . date('Y-m-d', strtotime($data['Date'])) . "','" . $data['forecast_act'] . "','" . $data['M_000'] . "','" . $data['M_010'] . "','" . $data['M_020'] . "','" . $data['M_030'] . "','" . $data['M_040'] . "','" . $data['M_050'] . "','" . $data['M_060'] . "','" . $data['M_070'] . "','" . $data['M_080'] . "','" . $data['M_090'] . "','" . $data['M_100'] . "','" . $data['M_120'] . "','" . $data['M_140'] . "','" . $data['M_170'] . "','" . $data['M_200'] . "','" . $data['M_250'] . "','" . $data['M_300'] . "','" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "','" . $data['total_DP'] . "','" . $data['total_SP'] . "','" . $data['Weekday'] . "','" . $data['Yield'] . "')";
			$result		 = DBUtil::command($insertQry)->execute();
		}
		$sqlDelete = "DELETE t1 FROM dynamic_price_surge t1 INNER JOIN dynamic_price_surge t2 ON t1.dps_id=t2.dps_id WHERE t1.dps_id < t2.dps_id AND t1.dps_name = t2.dps_name AND t1.Date = t2.Date";
		DBUtil::command($sqlDelete)->execute();
	}

	public function updateNewDDBPRouteData()
	{
		echo $sql	 = "DELETE FROM dynamic_price_surge WHERE Date>='2020-01-31'";
		$numrows = DBUtil::command($sql)->execute();
		echo "::$numrows\n\n";
		for ($i = 0; $i < 120; $i++)
		{
			echo $sql	 = "
			   INSERT INTO dynamic_price_surge (`dps_name`, `dps_name_ids`, `dps_src_name`, `dps_source_id`, `dps_dst_name`, `dps_dst_id`, `dps_area_type`, `additional_surge`, `base_capacity`, `count_booking`, `count_quotation`, `Date`, `forecast_act`, `M_000`, `M_010`, `M_020`, `M_030`, `M_040`, `M_050`, `M_060`, `M_070`, `M_080`, `M_090`, `M_100`, `M_120`, `M_140`, `M_170`, `M_200`, `M_250`, `M_300`, `manuual_count_booking`, `manuual_count_quotation`, `total_DP`, `total_SP`, `Weekday`, `Yield`)
			   SELECT `dps_name`, `dps_name_ids`, `dps_src_name`, `dps_source_id`, `dps_dst_name`, `dps_dst_id`, `dps_area_type`, `additional_surge`, `base_capacity`, '[0,0,0,0,0,0,0,0,0,0]', '[0,0,0,0,0,0,0,0,0,0]', DATE_ADD('2020-01-31', INTERVAL $i DAY), `forecast_act`, `M_000`, `M_010`, `M_020`, `M_030`, `M_040`, `M_050`, `M_060`, `M_070`, `M_080`, `M_090`, `M_100`, `M_120`, `M_140`, `M_170`, `M_200`, `M_250`, `M_300`, '[0,0,0,0,0,0,0,0,0,0]', '[0,0,0,0,0,0,0,0,0,0]', `total_DP`, `total_SP`, `Weekday`, `Yield`  FROM `dynamic_price_surge` WHERE DATE(Date)='2019-12-22'";
			$numrows = DBUtil::command($sql)->execute();
			echo "::$numrows\n\n";
		}
	}

	Public function updateDefaultDDBPRouteData()
	{
		$sql		 = "SELECT dps_name,dps_name_ids,dps_src_name,dps_source_id,dps_dst_name,dps_dst_id,base_capacity,count_booking,count_quotation,manuual_count_booking,manuual_count_quotation,total_DP,Weekday,Yield FROM `dynamic_default_price_surge` LIMIT 0,1";
		$recordSet	 = DBUtil:: queryAll($sql);
		foreach ($recordSet as $data)
		{
			$insertQry	 = "INSERT INTO dynamic_default_price_surge(dps_name,dps_name_ids,dps_src_name,dps_source_id,dps_dst_name,dps_dst_id,additional_surge,base_capacity,count_booking,count_quotation,Date,forecast_act,M_000,M_010,M_020,M_030,M_040,M_050,M_060,M_070,M_080,M_090,M_100,M_120,M_140,M_170,M_200,M_250,M_300,manuual_count_booking,manuual_count_quotation,total_DP,total_SP,Weekday,Yield) VALUES ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-02-22 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
            ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-02-23 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
            ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-02-24 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
            ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-02-25 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
            ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-02-26 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
            ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-02-27 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
             ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-02-28 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-02-29 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-02-30 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-02-31 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-01 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-02-02 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-02-03 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-04 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-05 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-06 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-07 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-08 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-09 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-10 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-11 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-12 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-13 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-14 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-15 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "'),
              ('" . $data['dps_name'] . "','" . $data['dps_name_ids'] . "','" . $data['dps_src_name'] . "','" . $data['dps_source_id'] . "','" . $data['dps_dst_name'] . "','" . $data['dps_dst_id'] . "','1','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','2020-03-16 00:00:00',1,1,1.1,1.1,1.1,1.2,1.2,1.2,1.3,1.3,1.3,1.4,1.5,1.6,1.7,1.8,2.0,2.5,'" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "',1,1,'" . $data['Weekday'] . "','" . $data['Yield'] . "');";
			//$insertQry; exit();
			$result		 = DBUtil::command($insertQry)->execute();
		}
	}

}
