<?php

/**
 * This is the model class for table "call_back_documents".
 *
 * The followings are the available columns in table 'call_back_documents':
 * @property string $cbd_id
 * @property string $cbd_scq_id
 * @property integer $cbd_scq_queue_type
 * @property string $cbd_created_at
 * @property string $cbd_modified_at
 * @property string $cbd_file_path
 * @property string $cbd_file_s3_data
 * @property integer $cbd_active
 */
class CallBackDocuments extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'call_back_documents';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cbd_scq_queue_type, cbd_created_at', 'required'),
            array('cbd_scq_queue_type, cbd_active', 'numerical', 'integerOnly' => true),
            array('cbd_scq_id', 'length', 'max' => 11),
            array('cbd_file_path', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('cbd_id, cbd_scq_id, cbd_modified_at, cbd_scq_queue_type, cbd_created_at, cbd_file_path, cbd_file_s3_data, cbd_active', 'safe', 'on' => 'search'),
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
            'cbd_id'             => 'Cbd',
            'cbd_scq_id'         => 'Cbd Scq',
            'cbd_scq_queue_type' => 'Cbd Scq Queue Type',
            'cbd_created_at'     => 'Cbd Created At',
            'cbd_file_path'      => 'Cbd File Path',
            'cbd_file_s3_data'   => 'Cbd File S3 Data',
            'cbd_active'         => 'Cbd Active',
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

        $criteria->compare('cbd_id', $this->cbd_id, true);
        $criteria->compare('cbd_scq_id', $this->cbd_scq_id, true);
        $criteria->compare('cbd_scq_queue_type', $this->cbd_scq_queue_type);
        $criteria->compare('cbd_created_at', $this->cbd_created_at, true);
        $criteria->compare('cbd_file_path', $this->cbd_file_path, true);
        $criteria->compare('cbd_file_s3_data', $this->cbd_file_s3_data, true);
        $criteria->compare('cbd_active', $this->cbd_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CallBackDocuments the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * This function is used to upload multiple file with respect to service call request
     * @param type $scqId
     * @return type
     * @throws Exception
     */
    public function upload($scqId = NULL)
    {
        $success = false;
        try
        {
            $scqModel  = ServiceCallQueue::model()->findByPk($scqId);
            $contactId = $scqModel->scq_to_be_followed_up_with_contact;
            if ($scqModel->scq_to_be_followed_up_with_type == 2)
            {
                $phnNumber = $scqModel->scq_to_be_followed_up_with_value;
            }
            $queueType = $scqModel->scq_follow_up_queue_type;
            $docFiles  = CUploadedFile::getInstances($this, 'cbd_file_path');
            if (isset($docFiles) && count($docFiles) > 0)
            {
                foreach ($docFiles as $key => $file)
                {
                    $file                      = self::uploadFiles($contactId, 'document', $file, $phnNumber);
                    $model                     = new CallBackDocuments();
                    $model->cbd_file_path      = $file;
                    $model->cbd_scq_id         = $scqId;
                    $model->cbd_scq_queue_type = $queueType;
                    $model->cbd_created_at     = DBUtil::getCurrentTime();
                    $success                   = $model->save();
                    if (!$success)
                    {
                        throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_INVALID_DATA);
                    }
                }
            }
        }
        catch (Exception $ex)
        {
            $errors = $ex->getMessage();
        }
        return $success;
    }

    /**
     * This function is used to upload files in a specified directory
     * for contact related file(scq/contact/...), number related file(scq/number/....) and other file(scq/other/) directories
     * @param type $cttId
     * @param type $doctypeName
     * @param CUploadedFile $file
     * @param type $phoneNumber
     * @param type $maxWidth
     * @return type
     */
    public static function uploadFiles($cttId, $doctypeName, CUploadedFile $file, $phoneNumber, $maxWidth = 1200)
    {
        $DS       = DIRECTORY_SEPARATOR;
        $fileName = $doctypeName . "-" . date('YmdHis').mt_rand(). "." . pathinfo($file->name, PATHINFO_EXTENSION);

        $basePath = Yii::app()->basePath;
        $date     = strtotime(DBUtil::getCurrentTime());
        $day      = date('d', $date);
        $month    = date('m', $date);
        $year     = date('Y', $date);
        $docPath  = $DS . 'doc' . $DS . Config::getServerID() . $DS . 'scq' . $DS . $year . $DS . $month . $DS . $day . $DS;
        $subpath  = $DS . Config::getServerID() . $DS . 'scq' . $DS . $year . $DS . $month . $DS . $day . $DS;
        if (!is_dir($basePath . $docPath))
        {
            $checkdir = mkdir($basePath . $docPath, 0755, true);
            if (!$checkdir)
            {
                echo "Failed to create dir: " . $basePath . $docPath;
                exit;
            }
        }

        $destinationPath = $basePath . $docPath . $fileName;

        if (Filter::checkImage($file->getType()))
        {
            Filter::resizeImage($file->tempName, $maxWidth, $destinationPath);
        }
        else
        {
            $file->saveAs($destinationPath);
        }

        return $subpath . $fileName;
    }

    public static function getDocImages($followUpId)
    {
        $sql      = "SELECT * FROM call_back_documents WHERE call_back_documents.cbd_scq_id = $followUpId";
        $sqlCount = "SELECT * FROM call_back_documents WHERE call_back_documents.cbd_scq_id = $followUpId";

        $count        = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
        $dataprovider = new CSqlDataProvider($sql, [
            'totalItemCount' => $count,
            'db'             => DBUtil::SDB(),
            'sort'           => ['attributes' => ['cdb_created_at']],
            'pagination'     => ['pageSize' => 20],
        ]);
        return $dataprovider;
    }

    /**
     * This is used to get image path with respect to docId
     * @param type $docId, $pathType
     * @return Doc path link
     */
    public static function getDocPathById($docId)
    {
        $path = '/images/no-image.png';

        $serviceCallDocModel = CallBackDocuments::model()->findByPk($docId);
        if (!$serviceCallDocModel)
        {
            goto end;
        }
        $s3Data  = $serviceCallDocModel->cbd_file_s3_data;
        $imgPath = $serviceCallDocModel->getLocalPath();
        if (file_exists($imgPath) && $imgPath != $serviceCallDocModel->getBaseDocPath())
        {
            if (substr_count($imgPath, PUBLIC_PATH) > 0)
            {
                $path = substr($imgPath, strlen(PUBLIC_PATH));
            }
            else
            {
                $path = AttachmentProcessing::publish($imgPath);
            }
        }
        else if ($s3Data != '{}' && $s3Data != '')
        {
            $spaceFile = \Stub\common\SpaceFile::populate($s3Data);
            $path      = $spaceFile->getURL();
            if ($spaceFile->isURLCreated())
            {
                $serviceCallDocModel->cbd_file_s3_data = $spaceFile->toJSON();
                $serviceCallDocModel->save();
            }
        }
        end:
        return $path;
    }

    public function getLocalPath()
    {
        $filePath = $this->cbd_file_path;
        $filePath = implode("/", explode(DIRECTORY_SEPARATOR, $filePath));
        $filePath = implode(DIRECTORY_SEPARATOR, explode("/", $filePath));
        $filePath = $this->getBaseDocPath() . $filePath;
        return $filePath;
    }

    public function getBaseDocPath()
    {
        return APPLICATION_PATH . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR;
    }

    public function getSpacePath($localPath)
    {
        $fileName = basename($localPath);
        $id       = $this->cbd_id;
        $docType  = $this->cbd_scq_queue_type;
        if ($docType == '')
        {
            $docType = 0;
        }
        $date       = $this->cbd_created_at;
        $dateString = DateTimeFormat::SQLDateTimeToDateTime($date)->format("Y/m/d");
        $path       = "/{$docType}/{$dateString}/{$id}_{$fileName}";
        return $path;
    }

    /**
     * @return Stub\common\SpaceFile
     */
    public function uploadToSpace($localFile, $spaceFile, $removeLocal = true)
    {
        $objSpaceFile = Storage::uploadFile(Storage::getCallBackDocSpace(), $spaceFile, $localFile, $removeLocal);
        return $objSpaceFile;
    }

    public static function uploadAllToS3($limit = 1000)
    {
        while ($limit > 0)
        {
            $limit1   = min([1000, $limit]);
            // Server Id
            $serverId = Config::getServerID();
            if ($serverId == '' || $serverId <= 0)
            {
                Logger::writeToConsole('Server ID not found!!!');
                break;
            }
            $where        = DIRECTORY_SEPARATOR . $serverId . DIRECTORY_SEPARATOR . "scq" . DIRECTORY_SEPARATOR;
//            $where        = "scq";
            $condFilePath = " AND (cbd_file_s3_data IS NULL AND cbd_file_path LIKE '%$where%') ";
            $sql          = "SELECT cbd_id FROM call_back_documents WHERE cbd_scq_id > 0 {$condFilePath} ORDER BY cbd_id DESC LIMIT 0, $limit1";
            $res          = DBUtil::query($sql,DBUtil::SDB());
            if ($res->getRowCount() == 0)
            {
                break;
            }
            foreach ($res as $row)
            {
                $callBackModel = CallBackDocuments::model()->findByPk($row['cbd_id']);
                $callBackModel->uploadToS3();
                $callBackModel->uploadFileToSpace();
                Logger::writeToConsole($callBackModel->cbd_file_s3_data);
            }

            $limit -= $limit1;
            Logger::flush();
        }
    }

    /** @return Stub\common\SpaceFile */
    public function uploadToS3($removeLocal = true)
    {
        $spaceFile = null;
        try
        {
            $callBackModel = $this;
            $path          = $this->getLocalPath();
            if (!file_exists($path) || $this->cbd_file_path == '')
            {
                if ($callBackModel->cbd_file_s3_data == '')
                {
                    $callBackModel->cbd_file_s3_data = "{}";
                    $callBackModel->save();
                }
                return null;
            }
            $spaceFile                       = $callBackModel->uploadToSpace($path, $this->getSpacePath($path), $removeLocal);
            $callBackModel->cbd_file_s3_data = $spaceFile->toJSON();
            $callBackModel->save();
        }
        catch (Exception $exc)
        {
            ReturnSet::setException($exc);
        }
        return $spaceFile;
    }

    public function uploadFileToSpace($removeLocal = true)
    {
        $spaceFile = null;
        try
        {
            $callBackModel = $this;
            $path          = $this->getLocationPath();
            if (!file_exists($path) || $callBackModel->cbd_file_path == '')
            {
                if ($callBackModel->cbd_file_s3_data == '')
                {
                    $callBackModel->cbd_file_s3_data = "{}";
                    $callBackModel->save();
                }
                return null;
            }

            $spaceFile                       = $callBackModel->uploadToSpace($path, $this->getSpacePath($path), $removeLocal);
            $callBackModel->cbd_file_s3_data = $spaceFile->toJSON();
            $callBackModel->save();
        }
        catch (Exception $exc)
        {
            ReturnSet::setException($exc);
        }
        return $spaceFile;
    }

    public function getLocationPath()
    {
        $filePath = $this->cbd_file_path;
        $filePath = $this->getBaseDocPath() . $filePath;
        return $filePath;
    }

}
