<?php

/**
 * This is the model class for table "ireaddocs".
 *
 * The followings are the available columns in table 'ireaddocs':
 * @property integer $ird_id
 * @property string $ird_transaction_id
 * @property string $ird_doc_id
 * @property string $ird_gimage_url
 * @property string $ird_rimage_url
 * @property integer $ird_type
 * @property integer $ird_doc_type
 * @property string $ird_response
 * @property string $ird_async_response
 * @property integer $ird_status
 * @property interger $ird_err_count
 * @property integer $ird_active
 * @property string $ird_created
 * @property string $ird_modified
 */
class Ireaddocs extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'ireaddocs';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ird_type, ird_doc_type, ird_status, ird_active,ird_err_count', 'numerical', 'integerOnly' => true),
            array('ird_transaction_id, ird_doc_id, ird_gimage_url, ird_rimage_url', 'length', 'max' => 5000),
            array('ird_response,ird_async_response', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('ird_id, ird_transaction_id, ird_doc_id, ird_gimage_url, ird_rimage_url, ird_type, ird_doc_type, ird_response, ird_status, ird_active, ird_created, ird_modified,ird_async_response,ird_err_count', 'safe', 'on' => 'search'),
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
            'ird_id'             => 'Ird',
            'ird_transaction_id' => 'Trascation Id',
            'ird_doc_id'         => 'Document Id',
            'ird_gimage_url'     => 'Gozo Image Url',
            'ird_rimage_url'     => 'Iread Image Url',
            'ird_type'           => 'Type',
            'ird_doc_type'       => 'Doc Type',
            'ird_response'       => 'Response',
            'ird_async_response' => 'Async Response',
            'ird_status'         => 'Status',
            'ird_err_count'      => "Error Count",
            'ird_active'         => 'Active',
            'ird_created'        => 'Created',
            'ird_modified'       => 'Modified',
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

        $criteria->compare('ird_id', $this->ird_id);
        $criteria->compare('ird_transaction_id', $this->ird_transaction_id, true);
        $criteria->compare('ird_doc_id', $this->ird_doc_id, true);
        $criteria->compare('ird_gimage_url', $this->ird_gimage_url, true);
        $criteria->compare('ird_rimage_url', $this->ird_rimage_url, true);
        $criteria->compare('ird_type', $this->ird_type);
        $criteria->compare('ird_doc_type', $this->ird_doc_type);
        $criteria->compare('ird_response', $this->ird_response, true);
        $criteria->compare('ird_async_response', $this->ird_async_response, true);
        $criteria->compare('ird_status', $this->ird_status);
        $criteria->compare('ird_active', $this->ird_active);
        $criteria->compare('ird_created', $this->ird_created, true);
        $criteria->compare('ird_modified', $this->ird_modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Ireaddocs the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * This function is used adding row to  iread docs
     * @param type $docId
     * @param type $docType
     * @param type $type
     * @return boolean
     */
    public static function add($docId, $docType, $type)
    {
        $model   = null;
        $success = false;
        try
        {
            $modelIread             = new Ireaddocs();
            $modelIread->ird_doc_id = $docId;
            if ($docType == 1)
            {
                $model  = Document::model()->findByPk($docId);
                $s3Data = $model->doc_front_s3_data;
            }
            else if ($docType == 2)
            {
                $model  = BookingPayDocs::model()->findByPk($docId);
                $s3Data = $model->bpay_s3_data;
            }
            else if ($docType == 3)
            {
                $model  = VehicleDocs::model()->findByPk($docId);
                $s3Data = $model->vhd_s3_data;
            }
            if ($s3Data != '{}' && $s3Data != '')
            {
                $spaceFile                  = Stub\common\SpaceFile::populate($s3Data);
                $modelIread->ird_gimage_url = $spaceFile->getURL();
                $modelIread->ird_type       = $type;
                $modelIread->ird_doc_type   = $docType;
                $modelIread->ird_status     = 1;
                $modelIread->ird_active     = 1;
                $modelIread->ird_created    = new CDbExpression('NOW()');
                $modelIread->ird_modified   = new CDbExpression('NOW()');
                $success                    = $modelIread->save();
            }
        }
        catch (Exception $ex)
        {
            Logger::exception($ex);
        }
        return $success;
    }

    /**
     * This function is used updating the  row to  iread docs
     * @param type $ireadId
     * @param type $response
     * @return boolean
     */
    public static function updateIread($ireadId, $response)
    {
        $success = false;
        try
        {
            $res                            = json_decode($response);
            $modelIread                     = self::model()->findByPk($ireadId);
            $modelIread->ird_rimage_url     = $res->img_path;
            $modelIread->ird_transaction_id = $res->transaction_id;
            $modelIread->ird_status         = 3;
            $modelIread->ird_response       = $response;
            $success                        = $modelIread->save();
        }
        catch (Exception $ex)
        {
            Logger::exception($ex);
        }
        return $success;
    }

    /**
     * This function is used updating the  async row to  iread docs
     * @param type $transactionId
     * @param type $response
     * @return boolean
     */
    public static function updateAsyncIread($transactionId, $response)
    {
        $success = false;
        try
        {
            $modelIread                     = self::model()->findByTransactionId($transactionId);
            $modelIread->ird_status         = 4;
            $modelIread->ird_async_response = $response;
            $success                        = $modelIread->save();
        }
        catch (Exception $ex)
        {
            Logger::exception($ex);
        }
        return $success;
    }

    /**
     * This function is getting all rows 
     * @param type $status
     * @return queryObject array
     */
    public static function getAllImage($status)
    {
        $sql = "SELECT * FROM ireaddocs WHERE ird_status =:status AND ird_active=1";
        return DBUtil::query($sql, DBUtil::SDB(), ['status' => $status]);
    }

    /**
     * This function is getting all rows 
     * @param type $transactionId
     * @return model
     */
    public function findByTransactionId($transactionId)
    {
        $model = $this::model()->find('ird_transaction_id=:transactionId AND ird_active=1', ['transactionId' => $transactionId]);
        return $model;
    }

    /**
     * This function is getting all rows for  document upload image 
     * @return query object
     */
    public static function getAllDocsImage()
    {
        $sql = 'SELECT
                doc_id AS docId,
                doc_type AS docType,
                1 AS "type"
                FROM document
                WHERE     1 
                AND doc_front_s3_data IS NOT NULL
                AND doc_created_at BETWEEN CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY)," 00:00:00") AND CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY)," 23:59:59")
                AND doc_active = 1
            
                UNION
            
                SELECT
                bpay_id AS docId,
                bpay_type AS docType,
                2 AS "type"
                FROM booking_pay_docs
                WHERE   1
                AND   bpay_s3_data IS NOT NULL
                AND bpay_type IN (8,9,107)
                AND bpay_date BETWEEN CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY)," 00:00:00") AND CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY)," 23:59:59")
                AND bpay_status =1
                
                UNION
                
                SELECT
                vhd_id AS docId,
                vhd_type AS docType,
                3 AS "type"
                FROM vehicle_docs
                WHERE 1 
                AND vhd_s3_data IS NOT NULL
                AND vhd_created_at BETWEEN CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY)," 00:00:00") AND CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY)," 23:59:59")
                AND vhd_active = 1
                AND vhd_type IN (2,3)
                AND vehicle_docs.vhd_status=0';
        return DBUtil::query($sql, DBUtil::SDB());
    }

}
