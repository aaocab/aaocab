<?php

/**
 * This is the model class for table "vehicle_category".
 *
 * The followings are the available columns in table 'vehicle_category':
 * @property integer $vct_id
 * @property string $vct_label
 * @property string $vct_desc
 * @property integer $vct_capacity
 * @property  string $vct_image
 * @property integer $vct_active
 * @property integer $vct_capacity
 * @property integer $vct_rank
 * @property string $vct_image
 * @property string $vct_create_date
 * @property string $vct_modified_date
 * @property SvcClassVhcCat $vct_SvcClassVhcCat
 * @property VcvCatVhcType $vct_VcvCatVhcType
 */
class VehicleCategory extends CActiveRecord
{

    const COMPACT_ECONOMIC		 = 1;
    const SUV_ECONOMIC			 = 2;
    const SEDAN_ECONOMIC			 = 3;
    const TEMPO_TRAVELLER_ECONOMIC	 = 4;
    const ASSURED_DZIRE_ECONOMIC		 = 5;
    const ASSURED_INNOVA_ECONOMIC		 = 6;
    const TEMPO_TRAVELLER_9_ECONOMIC	 = 7;
    const TEMPO_TRAVELLER_12_ECONOMIC	 = 8;
    const TEMPO_TRAVELLER_15_ECONOMIC	 = 9;
    const TEMPO_TRAVELLER_19_ECONOMIC	 = 10;
    const SHARED_SEDAN_ECONOMIC		 = 11;
    const TEMPO_TRAVELLER_26_ECONOMIC	 = 12;
    const SUV_7_PLUS_1_ECONOMIC		 = 13;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
	return 'vehicle_category';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
	// NOTE: you should only define rules for those attributes that
	// will receive user inputs.
	return array(
	    array('', 'required', 'except' => 'save'),
	    array('vct_active', 'numerical', 'integerOnly' => true),
	    array('vct_label', 'length', 'max' => 100),
	    array('vct_desc', 'length', 'max' => 255),
	    array('vct_label, vct_desc ,vct_capacity', 'required', 'on' => 'save'),
	    // The following rule is used by search().
	    // @todo Please remove those attributes that should not be searched.
	    array('vct_label, vct_desc, vct_active, vct_create_date, vct_modified_date,vct_capacity,vct_image,vct_rank', 'safe'),
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
	    'vct_VcvCatVhcType'	 => array(self::HAS_MANY, 'VcvCatVhcType', 'vcv_vct_id'),
	    'vct_SvcClassVhcCat'	 => array(self::HAS_MANY, 'SvcClassVhcCat', 'scv_vct_id'),
	);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
	return array(
	    'vct_id'		 => 'Vct',
	    'vct_label'		 => 'label',
	    'vct_desc'		 => 'description',
	    'vct_capacity'		 => 'capacity',
	    'vct_image'		 => 'image',
	    'vct_active'		 => 'Vct Active',
	    'vct_create_date'	 => 'Vct Create Date',
	    'vct_modified_date'	 => 'Vct Modified Date',
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

	$criteria->compare('vct_id', $this->vct_id);
	$criteria->compare('vct_label', $this->vct_label, true);
	$criteria->compare('vct_desc', $this->vct_desc, true);
	$criteria->compare('vct_capacity', $this->vct_capacity, true);
	$criteria->compare('vct_image', $this->vct_image, true);
	$criteria->compare('vct_active', $this->vct_active);
	$criteria->compare('vct_create_date', $this->vct_create_date, true);
	$criteria->compare('vct_modified_date', $this->vct_modified_date, true);

	return new CActiveDataProvider($this, array(
	    'criteria' => $criteria,
	));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return VehicleCategory the static model class
     */
    public static function model($className = __CLASS__)
    {
	return parent::model($className);
    }

    public function getList($type = '')
    {
	$cond	 = "";
	$sql	 = "SELECT vehicle_category.*
                FROM `vehicle_category`  ";
	if ($this->vct_label != '')
	{
	    $cond .= " AND vct_label LIKE '%" . $this->vct_label . "%'";
	} if ($this->vct_desc != '')
	{
	    $cond .= " AND vct_desc LIKE '%" . $this->vct_desc . "%'";
	}
	$sql	 .= 'WHERE 1' . $cond;
	if ($type	 = 'list')
	{
	    return DBUtil::queryAll($sql);
	}
	$count		 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
	$dataprovider	 = new CSqlDataProvider($sql, [
	    'totalItemCount' => $count,
	    'sort'		 => ['attributes'	 => ['vct_label', 'vct_desc', 'scc_is_cng', 'scc_is_petrol_diesel', 'vct_active'],
		'defaultOrder'	 => 'vct_id ASC'],
	    'pagination'	 => ['pageSize' => 10],
	]);
	return $dataprovider;
    }

    public function uploadDocument($category_id, $doctypeName, $uploadedPhoto, $uploadedBackPhoto)
    {
	$DS			 = DIRECTORY_SEPARATOR;
	$categoryfileName	 = $doctypeName . "-" . date('YmdHis') . "." . pathinfo($uploadedPhoto->name, PATHINFO_EXTENSION);

	$path	 = PUBLIC_PATH;
	$docType = $path . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'car_category';

	if (!is_dir($docType))
	{
	    mkdir($docType, 775, true);
	}

	$foldertoupload = $docType . DIRECTORY_SEPARATOR . $categoryfileName;

	$categoryextension = pathinfo($uploadedPhoto, PATHINFO_EXTENSION);

	if (strtolower($categoryextension) == 'png' || strtolower($categoryextension) == 'jpg' || strtolower($categoryextension) == 'jpeg' || strtolower($categoryextension) == 'gif')
	{
	    Filter::resizeImage($uploadedPhoto->tempName, 1200, $docType . DIRECTORY_SEPARATOR . $categoryfileName);
	}
	else
	{
	    $uploadedPhoto->saveAs($foldertoupload);
	}

	$frontpath = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'car_category' . DIRECTORY_SEPARATOR . $categoryfileName;

	$result = [$frontpath];
	return $result;
    }

    public function getModelDetailsbyId($vctId)
    {
	$cdb = Yii::app()->db->createCommand()
		->select("vct_id, vct_label, vct_desc,vct_small_bag_capacity,vct_image,
						vct_capacity, vct_big_bag_capacity")
		->from('vehicle_category')
		->where('vct_id = ' . $vctId);

	$query = $cdb->queryRow();

	return $query;
    }

    /**
     * This function is used for finding the cab type based on booking id
     * @param type $bkgId
     * @return string
     */
    public function getCabByBkgId($bkgId)
    {
	if (empty($bkgId))
	{
	    return "";
	}

	$sql = "
			SELECT CONCAT(sc.scc_label, '(', vct.vct_label, ')') AS cab
			FROM vehicle_category vct
				INNER JOIN booking ON booking.bkg_vehicle_type_id = vct.vct_id
				INNER JOIN svc_class_vhc_cat scv
				ON scv.scv_id = booking.bkg_vehicle_type_id
			INNER JOIN service_class sc ON scv.scv_scc_id = sc.scc_id
			WHERE booking.bkg_id = $bkgId
		";

	$cab	 = DBUtil::command($sql)->queryScalar();
	$cab	 = ($cab != '') ? $cab : '';

	return $cab;
    }

    public function getCabNameBkgId($bkgId)
    {
	if (empty($bkgId))
	{
	    return "";
	}

	$sql = "
			SELECT CONCAT(vht_make,' ',vht_model) as cab
			FROM booking
            INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id
           INNER JOIN vehicles ON vehicles.vhc_id = booking_cab.bcb_cab_id
			 INNER JOIN vehicle_types ON vehicles.vhc_type_id = vehicle_types.vht_id
			WHERE booking.bkg_id =  $bkgId
		";

	$cab	 = DBUtil::command($sql)->queryScalar();
	$cab	 = ($cab != '') ? $cab : '';
	return $cab;
    }

    /**
     * This function is used for fetching the category list details
     * @param type $requestDetails
     * @return \CSqlDataProvider
     */
    public static function fetchVehicleCategoryDetalis($requestDetails = null)
    {
	$vehicleCategoryLabel	 = $requestDetails["vehicleCategoryLabel"];
	$vehicleCategoryDesc	 = $requestDetails["vehicleCategoryDesc"];

	$fetchVehicleCategoryDetails = "
			SELECT	vc.vct_id,
					vc.vct_label,
					vc.vct_desc,
					vc.vct_active
			FROM vehicle_category vc
			WHERE vc.vct_active = 1
		";

	if (!empty($vehicleCategoryLabel))
	{
	    $fetchVehicleCategoryDetails .= " AND vct_label LIKE '%$vehicleCategoryLabel%'";
	}

	if (!empty($vehicleCategoryDesc))
	{
	    $fetchVehicleCategoryDetails .= " AND vct_desc LIKE '%$vehicleCategoryDesc%'";
	}

	$count		 = DBUtil::command("SELECT COUNT(*) FROM ($fetchVehicleCategoryDetails) abc")->queryScalar();
	$dataprovider	 = new CSqlDataProvider($fetchVehicleCategoryDetails,
				       [
	    "totalItemCount" => $count,
	    "pagination"	 =>
	    [
		"pageSize" => 50
	    ],
	]);

	return $dataprovider;
    }
    
    public function getTypeClassbyid($vhcid)
	{
		$param	 = ['vhcid' => $vhcid];
		$sql	 = "SELECT DISTINCT vht1.vct_label,vcv.vcv_vct_id,scc.scc_label
					FROM   vehicles vhc				 
					INNER JOIN `vehicle_types` vht ON vht.vht_id=vhc.vhc_type_id
					INNER JOIN `vcv_cat_vhc_type` vcv ON vcv.vcv_vht_id=vht.vht_id
                    INNER JOIN vehicle_category vht1 ON vht1.vct_id = vcv.vcv_vct_id
                    INNER JOIN svc_class_vhc_cat scv ON scv.scv_id= vcv.vcv_vct_id
                    INNER JOIN service_class scc ON scc.scc_id =scv.scv_scc_id
					WHERE  vhc_active = 1 AND  vht.vht_active = 1 AND 
					vhc_id = :vhcid";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		return $result;
	}
	public static function getCat()
	{
		$sql = "SELECT vct_id, vct_label FROM vehicle_category ";
		$arr = DBUtil::query($sql, DBUtil::SDB());
		return CHtml::listData($arr, "vct_id", "vct_label");
	}

	public function getNameById($id)
	{
		return $this->model()->findByPk($id)->vct_label;
	}

	public function getJSON($arr = [])
	{
		$arrJSON = array();
		foreach ($arr as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

}
