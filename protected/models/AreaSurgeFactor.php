<?php

/**
 * This is the model class for table "area_surge_factor".
 *
 * The followings are the available columns in table 'area_surge_factor':
 * @property integer $asf_id
 * @property integer $asf_from_area_type
 * @property integer $asf_from_area_id
 * @property integer $asf_to_area_type
 * @property integer $asf_to_area_id
 * @property integer $asf_vehicle_type
 * @property integer $asf_trip_type
 * @property integer $asf_value_type
 * @property integer $asf_value
 * @property integer $asf_active
 */
class AreaSurgeFactor extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'area_surge_factor';
	}

	public $areatype = [1 => 'Zone', 2 => 'State', 3 => 'City', 4 => 'Region'];
	public $asf_area_name, $asf_area_id1, $asf_area_id2;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('asf_from_area_type, asf_from_area_id, asf_to_area_type, asf_to_area_id, asf_value', 'required', 'on' => 'insert'),
			array('asf_from_area_type, asf_from_area_id, asf_to_area_type, asf_to_area_id, asf_vehicle_type, asf_trip_type, asf_value_type, asf_value, asf_active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('asf_id, asf_from_area_type, asf_from_area_id, asf_to_area_type, asf_to_area_id, asf_vehicle_type, asf_trip_type, asf_value_type, asf_value, asf_active', 'safe', 'on' => 'search'),
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
			'asf_id'			 => 'Asf',
			'asf_from_area_type' => 'Asf From Area Type',
			'asf_from_area_id'	 => 'Asf From Area',
			'asf_to_area_type'	 => 'Asf To Area Type',
			'asf_to_area_id'	 => 'Asf To Area',
			'asf_vehicle_type'	 => 'Asf Vehicle Type',
			'asf_trip_type'		 => 'Asf Trip Type',
			'asf_value_type'	 => 'Asf Value Type',
			'asf_value'			 => 'Value',
			'asf_active'		 => 'Asf Active',
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

		$criteria->compare('asf_id', $this->asf_id);
		$criteria->compare('asf_from_area_type', $this->asf_from_area_type);
		$criteria->compare('asf_from_area_id', $this->asf_from_area_id);
		$criteria->compare('asf_to_area_type', $this->asf_to_area_type);
		$criteria->compare('asf_to_area_id', $this->asf_to_area_id);
		$criteria->compare('asf_vehicle_type', $this->asf_vehicle_type);
		$criteria->compare('asf_trip_type', $this->asf_trip_type);
		$criteria->compare('asf_value_type', $this->asf_value_type);
		$criteria->compare('asf_value', $this->asf_value);
		$criteria->compare('asf_active', $this->asf_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AreaSurgeFactor the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	
	public static function addUpdateAreaSurge($receivedData, $areaSurgeId = null)
	{
		//Return 0 if the form data is not passed
		if (empty($receivedData))
		{
			return 0;
		}

		/**
		 * Case 1: If areaSurgeId passed -> Then update the details
		 * Case 2: If areaSurgedId missing -> Then add a new entry to the area surge factor table
		 */
		if (!empty($areaSurgeId))
		{
			$areaSurgeModel = AreaSurgeFactor::model()->findByPk($areaSurgeId);
		}
		else
		{
			$areaSurgeModel				 = new AreaSurgeFactor();
			$areaSurgeModel->asf_created = new CDbExpression('NOW()');
			$areaSurgeModel->asf_active	 = 1;
		}


		$areaSurgeModel->asf_from_area_type	 = $receivedData["asf_from_area_type"];
		$areaSurgeModel->asf_from_area_id	 = $receivedData["asf_from_area_id"];
		$areaSurgeModel->asf_to_area_type	 = $receivedData["asf_to_area_type"];
		$areaSurgeModel->asf_to_area_id		 = $receivedData["asf_to_area_id"];
		$areaSurgeModel->asf_value_type		 = $receivedData["asf_value_type"];
		$areaSurgeModel->asf_value			 = $receivedData["asf_value"];
		$areaSurgeModel->asf_vehicle_type	 = $receivedData["asf_vehicle_type"];
		$areaSurgeModel->asf_trip_type		 = $receivedData["asf_trip_type"];

		if ($areaSurgeModel->save())
		{
			return $areaSurgeModel;
		}
		else
		{
			return 0;
		}
	}

	public function getList($type = DBUtil::ReturnType_Provider)
	{
		$condition = "WHERE asf_active = 1";

		if ($this->asf_from_area_type != '')
		{
			$condition .= " AND asf_from_area_type ='{$this->asf_from_area_type}'";
		}
		if ($this->asf_from_area_id != '')
		{
			$condition .= " AND asf_from_area_id ='{$this->asf_from_area_id}'";
		}
		if($this->asf_to_area_type != '')
		{
			$condition .= " AND asf_to_area_type = '{$this->asf_to_area_type}'";
		}
		if($this->asf_to_area_id != '')
		{
			$condition .= " AND asf_to_area_id = '{$this->asf_to_area_id}'";
		}

		if($this->asf_vehicle_type != '')
		{
			$condition .= " AND asf_vehicle_type = '{$this->asf_vehicle_type}'";
		}
		if($this->asf_trip_type != '')
		{
			$condition .= " AND asf_trip_type = '{$this->asf_trip_type}'";
		}

		$groupBy = ' GROUP BY asf_id';

		$sql = "SELECT asf.*, 
						if(asf.asf_from_area_type=1,frmzn.zon_name,'') AS fzoneName,
						if(asf.asf_from_area_type=2,frmst.stt_name,'') AS fstateName,
						if(asf.asf_from_area_type=3,frmct.cty_name,'') AS fcityName,
						if(asf.asf_to_area_type=1,tozn.zon_name,'') AS tzoneName,
						if(asf.asf_to_area_type=2,tost.stt_name,'') AS tstateName,
						if(asf.asf_to_area_type=3,toct.cty_name,'') AS tcityName
						FROM area_surge_factor asf
						LEFT JOIN cities frmct ON frmct.cty_id  = asf.asf_from_area_id
						LEFT JOIN states frmst ON frmst.stt_id  = asf.asf_from_area_id
						LEFT JOIN zones frmzn ON frmzn.zon_id  = asf.asf_from_area_id
						LEFT JOIN cities toct ON toct.cty_id = asf.asf_to_area_id
						LEFT JOIN states tost ON tost.stt_id = asf.asf_to_area_id
						LEFT JOIN zones tozn ON tozn.zon_id = asf.asf_to_area_id
						
						$condition $groupBy";

		$count = DBUtil::command("SELECT COUNT(DISTINCT asf_id) FROM area_surge_factor asf $condition")->queryScalar();

		if ($type == DBUtil::ReturnType_Provider)
		{
			$dataprovider = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['defaultOrder' => 'asf_id DESC'],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
	}

}
