<?php

/**
 * This is the model class for table "vcv_cat_vhc_type".
 *
 * The followings are the available columns in table 'vcv_cat_vhc_type':
 * @property integer $vcv_id
 * @property integer $vcv_vct_id
 * @property integer $vcv_vht_id
 * @property integer $vcv_active
 * @property VehicleCategory $vcv_VehicleCategory
 * @property VehicleTypes $vcv_VehicleTypes
 */
class VcvCatVhcType extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'vcv_cat_vhc_type';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('vcv_vct_id, vcv_vht_id', 'required'),
            array('vcv_vct_id, vcv_vht_id, vcv_active', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('vcv_id, vcv_vct_id, vcv_vht_id, vcv_active', 'safe', 'on' => 'search'),
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
            'vcv_VehicleTypes'    => array(self::BELONGS_TO, 'VehicleTypes', 'vcv_vht_id'),
            'vcv_VehicleCategory' => array(self::BELONGS_TO, 'VehicleCategory', 'vcv_vct_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'vcv_id'     => 'Vcv',
            'vcv_vct_id' => 'Vcv Vct',
            'vcv_vht_id' => 'Vcv Vht',
            'vcv_active' => 'Vcv Active',
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

        $criteria->compare('vcv_id', $this->vcv_id);
        $criteria->compare('vcv_vct_id', $this->vcv_vct_id);
        $criteria->compare('vcv_vht_id', $this->vcv_vht_id);
        $criteria->compare('vcv_active', $this->vcv_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return VcvCatVhcType the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getVehicleCatId($vhcTypeId)
    {
        $sql = "select vcv_vct_id from vcv_cat_vhc_type where vcv_active = 1 AND vcv_vht_id = $vhcTypeId";
        return DBUtil::command($sql)->queryScalar();
    }

    /**
     * This model function is used for fetching the vehicle category details
     * @param [int] $vehicleCategoryId
     * @return [Array] $arrCategoryDetails
     */
    public function getVehicleCategories($vehicleCategoryId = null, $returnType = null)
    {
        $fetchCategoryDetails = "
			SELECT vct_id, vct_label, vct_desc
			FROM vehicle_category
			WHERE vct_active = 1
		";

        if (!empty($vehicleCategoryId))
        {
            $fetchCategoryDetails .= " AND vct_id = $vehicleCategoryId";
        }

        $arrCategoryDetails = DBUtil::queryAll($fetchCategoryDetails, DBUtil::SDB());

        if ($returnType == "list")
        {
            return CHtml::listData($arrCategoryDetails, "vct_id", "vct_label");
        }

        return $arrCategoryDetails;
    }

    /**
     * This function is used for updating the vehicle_type and vehicle_category relation 
     * table.
     * 
     * @param type $vehicleTypeId
     * @param type $vctId
     * @return int
     */
    public static function updateRelationMapping($vehicleTypeId, $vctId = null)
    {
        /**
         * Case 1: If vehicleTypeId or vctId null or 0: No action will taken and return false;
         * Case 2: If vehicleTypeId or vctId not null: 
         * 			*	Check for existence.
         * 			*	If exists: Update the previous relationship
         * 			*	If not exists: Insert the record if $vcvId exists
         */
        $res = false;
        if (empty($vehicleTypeId) || empty($vctId))
        {
            return $res;
        }

        //Checks for mapping existence
        $checkIsExistsQuery = "
			SELECT vcv_id
			FROM vcv_cat_vhc_type
			WHERE vcv_vht_id = $vehicleTypeId 
			  AND vcv_active = 1
		";

        $arrDetails = DBUtil::queryAll($checkIsExistsQuery, DBUtil::SDB());

        $vcvIdFromDb = !empty($arrDetails[0]["vcv_id"]) ? $arrDetails[0]["vcv_id"] : null; //Existing vcvId
        //Insert new records if vcvIdFromDb is null or empty
        if (empty($vcvIdFromDb) && !empty($vctId))
        {
            $newVcvCatVhcTypeModel = new VcvCatVhcType();

            $newVcvCatVhcTypeModel->vcv_vct_id = $vctId;
            $newVcvCatVhcTypeModel->vcv_vht_id = $vehicleTypeId;
            $newVcvCatVhcTypeModel->vcv_active = 1;

            $newVcvCatVhcTypeModel->save();
        }

        //Update existing record if previous vcvId exists
        $updateVcvCatVhcType = "
			UPDATE vcv_cat_vhc_type
			SET vcv_vct_id = $vctId, vcv_vht_id = $vehicleTypeId
			WHERE vcv_id = $vcvIdFromDb
			AND vcv_active = 1
		";

        $res = DBUtil::command($updateVcvCatVhcType)->execute();
        return $res;
    }

    /**
     * This function is used for finding the vehicle type and category 
     * relationship and also for editing or insert.
     */
    public function VTypeVCatMapping($returnType = null)
    {
        //default response
        $arrResponse = array
            (
            "success"    => false,
            "statusCode" => 500,
            "message"    => "Failed",
            "data"       => null
        );


        //Fetching all vehicle types
        $fetchAllVehicleTypes = "
			SELECT vht_id, vht_model
			FROM vehicle_types
			WHERE vht_active = 1
		";

        $arrVehicleTypeDetails = DBUtil::queryAll($fetchAllVehicleTypes, DBUtil::SDB());

        if (empty($arrVehicleTypeDetails))
        {
            $arrResponse["Message"] = "No vehicle type found";
            goto skipAll;
        }

        //Fetching all vehicle categories
        $fetchAllVehicleCategories = "
			SELECT vct_id, vct_label
			FROM vehicle_category
			WHERE vct_active = 1
		";

        $arrVehicleCategoryDetails = DBUtil::queryAll($fetchAllVehicleCategories, DBUtil::SDB());

        if (empty($arrVehicleCategoryDetails))
        {
            $arrResponse["Message"] = "No vehicle category found";
            goto skipAll;
        }

        $index = 0;
        foreach ($arrVehicleCategoryDetails as $value)
        {
            $categoryClass[$index]["headerKeyId"] = $value["vct_id"];
            $categoryClass[$index]["headerLabel"] = $value["vct_label"];

            $index++;
        }

        /**
         * finding the relationship
         * Example : VT - Vehicle Type, VC = Vehicle Category
         * VT1 x VC1 = VCVVHT_ID
         * VT1 x VC2 = VCVVHT_ID
         */
        $vhtIndex    = 0;
        $vehicleData = [];
        foreach ($arrVehicleTypeDetails as $vehicleType)
        {
            $vhtId = $vehicleType["vht_id"];

            $vehicleData[$vhtIndex]["keyId"]   = $vhtId;
            $vehicleData[$vhtIndex]["keyDesc"] = $vehicleType["vht_model"];

            $vctIndex = 0;
            $vctData  = [];
            foreach ($arrVehicleCategoryDetails as $vehicleCategory)
            {
                $vctId           = $vehicleCategory["vct_id"];
                $validateMapping = Lookup::findVcVtMapping($vctId, $vhtId);

                $isMap    = 0; //Default, If no mapping found
                $vcvId    = 0; //Default, If no mapping found
                $isEdit   = 1;
                $isActive = 0;

                if ($validateMapping->vcvId > 0)
                {
                    $isMap    = 1;
                    $vcvId    = $validateMapping->vcvId;
                    $isActive = $validateMapping->isActive;
                }

                $vctData[$vctIndex]["mapKeyId"]      = $vcvId;
                $vctData[$vctIndex]["keyId"]         = $vctId;
                $vctData[$vctIndex]["relationKeyId"] = $vhtId;
                $vctData[$vctIndex]["keyName"]       = $vehicleCategory["vct_label"];
                $vctData[$vctIndex]["isMap"]         = $isMap;
                $vctData[$vctIndex]["isActive"]      = $isActive;
                $vctData[$vctIndex]["isEdit"]        = $isEdit; //Default TODO:Stub

                $vctIndex++;
            }

            $vehicleData[$vhtIndex]["subCat"] = $vctData;

            $vhtIndex++;
        }

        $createButtons = array();

        $temp = new stdClass();

        $temp->name   = "Add Model";
        $temp->target = "_blank";
        $temp->class  = "btn btn-primary mb10";
        $temp->url    = "admin/vehicle/addtype";
        $temp->style  = "text-decoration: none;margin-right: 15px;float: right;";

        array_push($createButtons, $temp);

        $temp = new stdClass();

        $temp->name   = "Add Category";
        $temp->target = "_blank";
        $temp->class  = "btn btn-primary mb10";
        $temp->url    = "admin/vehicle/addcategory";
        $temp->style  = "text-decoration: none;margin-right: 15px;float: right;";

        array_push($createButtons, $temp);

        $arrResponse["success"]               = true;
        $arrResponse["statusCode"]            = 200;
        $arrResponse["message"]               = "Successfully fetched";
        $arrResponse["data"]["headerType"]    = "Vehicle Category \ Vehicle Type";
        $arrResponse["data"]["createButtons"] = $createButtons;
        $arrResponse["data"]["headerRow"]     = $categoryClass;
        $arrResponse["data"]["rowData"]       = $vehicleData;

        if (!empty($returnType) && $returnType == "array")
        {
            return $arrResponse;
        }
        skipAll:

        echo json_encode($arrResponse);
        exit;
    }

    /**
     * This function is used for updating the vehicle category and 
     * vehicle type mapping and insertion
     * @param type $receivedData
     */
    public static function updateVcVtMapping($receivedData)
    {
        $arrResponse = array
            (
            "success" => false,
            "message" => "",
        );

        $success       = 0;
        $newMapping    = $receivedData->newMapping;
        $updateMapping = $receivedData->updateMapping;

        if (empty($newMapping))
        {
            goto skipNew;
        }

        //For new mappings
        $success = 1;
        foreach ($newMapping as $value)
        {
            $newVcvCatVhcTypeModel = new VcvCatVhcType();

            $newVcvCatVhcTypeModel->vcv_vct_id = $value->keyId;
            $newVcvCatVhcTypeModel->vcv_vht_id = $value->relationKeyId;
            $newVcvCatVhcTypeModel->vcv_active = 1;

            $newVcvCatVhcTypeModel->save();
        }

        skipNew:

        if (empty($updateMapping))
        {
            goto skipAll;
        }

        //For updating the existing records
        $success = 1;
        foreach ($updateMapping as $value)
        {
            $vcvId    = $value->mapKeyId;
            $isActive = (int) $value->isActive;

            //Update existing record if previous vcvId exists
            $updateVcvCatVhcType = "
				UPDATE vcv_cat_vhc_type
				SET vcv_active = $isActive
				WHERE vcv_id = $vcvId
			";

            DBUtil::command($updateVcvCatVhcType, DBUtil::MDB())->execute();
        }

        skipAll:

        if ($success)
        {
            $arrResponse["success"] = true;
            $arrResponse["message"] = "Successfully update the details";
        }


        return $arrResponse;
    }

    public static function addCategoryMapping($vehicleTypeId, $vctId = null)
    {
        $success = false;
        if (empty($vehicleTypeId) || empty($vctId))
        {
            return $success;
        }
        //Checks for mapping existence
        $checkIsExistsQuery = "SELECT vcv_id FROM vcv_cat_vhc_type WHERE vcv_vht_id = $vehicleTypeId AND vcv_active = 1";
        $arrDetails         = DBUtil::queryRow($checkIsExistsQuery, DBUtil::SDB());
        $vcvIdFromDb        = !empty($arrDetails["vcv_id"]) ? $arrDetails["vcv_id"] : null; //Existing vcvId

        if (empty($vcvIdFromDb) && !empty($vctId))
        {
            $newVcvCatVhcTypeModel = new VcvCatVhcType();

            $newVcvCatVhcTypeModel->vcv_vct_id = $vctId;
            $newVcvCatVhcTypeModel->vcv_vht_id = $vehicleTypeId;
            $newVcvCatVhcTypeModel->vcv_active = 1;
            if ($newVcvCatVhcTypeModel->save())
            {
                $success = true;
            }
        }
        else
        {
            $updateVcvCatVhcType = "UPDATE vcv_cat_vhc_type SET vcv_vct_id = $vctId, vcv_vht_id = $vehicleTypeId
									WHERE vcv_id = $vcvIdFromDb AND vcv_active = 1";
            $result              = DBUtil::command($updateVcvCatVhcType)->execute();
            $success             = ($result > 0) ? true : false;
        }
        return $success;
    }


}
